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
    use \MvcFramework\Exception\Exception;
    
    class StringLength extends ValidatorAbstract
    {
        /**
         * Too short error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const TOO_SHORT = 'tooShort';

        /**
         * Too long error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const TOO_LONG = 'tooLong';

        /**
         * Unknown error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const UNKNOWN = 'unknown';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::TOO_SHORT     => 'String is less than than %d characters in length',
            self::TOO_LONG      => 'String is greater than %d characters in length',
            self::UNKNOWN       => 'String length is invalid',
        );

        /**
         * Error message template variables.
         *
         * @access protected
         * @var array
         */
        protected $_messageVariables = array(
            'min' => array('options' => 'min'),
            'max' => array('options' => 'max'),
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
            // Trim whitespace?
            if ( (isset($this->_options['trimWhitespace'])) 
                    && (true === $this->_options['trimWhitespace']) ){

                $filter = new \MvcFramework\Filter\TrimWhitespace;
                $value = $filter->filter($value);
            }

            $max = false;
            if (isset($this->_options['max'])){
                $max = (int)$this->_options['max'];
            }

            $min = false;
            if (isset($this->_options['min'])){
                $min = (int)$this->_options['min'];
            }

            if (false === $max && false === $min){
                throw new Exception('No max or min value to
                        check values against');
            }

            // Are both values set?
            if (is_int($min) && is_int($max)){

                if ($min > $max){
                    throw new Exception('Min value greater than max.');
                }

                if ( (strlen($value) >= $min) && (strlen($value) <= $max) ){
                    return true;
                }else{
                    if (strlen($value) < $min){
                        $this->_addMessage(self::TOO_SHORT, [$min]);
                        return false;
                    }elseif(strlen($value) > $max){
                        $this->_addMessage(self::TOO_LONG, [$max]);
                        return false;
                    }
                }
            }

            // Is min only set?
            if (is_int($min)){
                if (strlen($value) < $min){
                    $this->_addMessage(self::TOO_SHORT, [$min]);
                    return false;
                }else{
                    return true;
                }
            }

            // Is max only set?
            if (is_int($max)){
                if (strlen($value) > $max){
                    $this->_addMessage(self::TOO_LONG, [$max]);
                    return false;
                }else{
                    return true;
                }
            }


            // Code flow shouldnt get this far, but just in case:
            $this->_addMessage(self::UNKNOWN);
            return false;
        }
    }
}