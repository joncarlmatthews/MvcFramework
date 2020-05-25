<?php

/**
 * MvcFramework
 *
 * @link        https://github.com/joncarlmatthews/MvcFramework for the canonical source repository
 * @copyright   
 * @link        Coded to the Zend Framework Coding Standard for PHP 
 *              http://framework.zend.com/manual/1.12/en/coding-standard.html
 * 
 * File format: UNIX
 * File encoding: UTF8
 * File indentation: Spaces (4). No tabs
 *
 */

namespace MvcFramework\Validator
{
    /**
     * EmailAddress validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class EmailAddress extends ValidatorAbstract
    {
        /**
         * Invalid message template key
         *
         * @static
         * @access public
         * @param string
         */
        const IS_INVALID = 'isInvalid';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::IS_INVALID => 'Email address is invalid',
        );

        /**
         * Is valid method.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $value
         * @return bool
         */
        public function isValid($value)
        {
            $isValid = true;
            $atIndex = strrpos($value, "@");
            
            if (is_bool($atIndex) && !$atIndex){

                $isValid = false;

            }else{

                $domain = substr($value, $atIndex+1);
                $local = substr($value, 0, $atIndex);
                $localLen = strlen($local);
                $domainLen = strlen($domain);

                if ($localLen < 1 || $localLen > 64){
                    // local part length exceeded
                    $isValid = false;
                }else if ($domainLen < 1 || $domainLen > 255){
                    // domain part length exceeded
                    $isValid = false;
                } else if ($local[0] == '.' || $local[$localLen-1] == '.'){
                    // local part starts or ends with '.'
                    $isValid = false;
                }else if (preg_match('/\\.\\./', $local)){
                    // local part has two consecutive dots
                    $isValid = false;
                }else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
                    // character not valid in domain part
                    $isValid = false;
                } else if (preg_match('/\\.\\./', $domain)){
                    // domain part has two consecutive dots
                    $isValid = false;
                }else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                        str_replace("\\\\","",$local))){
                    // character not valid in local part unless
                    // local part is quoted
                    if (!preg_match('/^"(\\\\"|[^"])+"$/',
                            str_replace("\\\\","",$local))){
                        $isValid = false;
                    }
                }

                // DNS Lookups...

                // I've broken the code flow out here, as originally checkdnsrr
                // was run automatically for both MX and A record lookups 
                // regardless. You now have to explicitly opt in for either
                // MX or A record lookups. The reason for doing this is that
                // checkdnsrr calls could take between 15 to 65 seconds for 
                // an invalid domain.

                // MX DNS Lookup?
                if ($isValid){
                    
                    if ( (isset($this->_options['dnsMXLookup'])) 
                            && (true == $this->_options['dnsMXLookup']) ){

                        if (!checkdnsrr($domain, 'MX')){
                            // domain not found in DNS
                            $isValid = false;
                        }

                    }
                }

                // A record DNS Lookup?
                if ($isValid){

                    if ( (isset($this->_options['dnsALookup'])) 
                            && (true == $this->_options['dnsALookup']) ){

                        if (!checkdnsrr($domain, 'A')){
                            // domain not found in DNS
                            $isValid = false;
                        }

                    }
                }

            }
            
            if ($isValid){
                return true;
            }else{
                $this->_addMessage(self::IS_INVALID);
                return false;
            }
        }
    }
}