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
    
    class NumericRange extends ValidatorAbstract
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
         * Not numeric error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const NOT_NUMERIC = 'notNumeric';
        
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
            self::TOO_SHORT     => 'Number is less than than %%min%%',
            self::TOO_LONG      => 'Number is greater than %%max%%',
            self::NOT_NUMERIC   => '%%value%% is not numeric',
            self::UNKNOWN       => 'Number length is invalid',
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
         * @author  Greg Burke <greg.burke@swc.com>
         * @param string $value
         * @return bool
         */
        public function isValid($value)
        {
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

            // Is the value numeric?
            if (!is_numeric($value)){
                $this->_addMessage(self::NOT_NUMERIC);
                return false;
            }
            
            // Are both values set?
            if (is_numeric($min) && is_numeric($max)){

                if ($min > $max){
                    throw new Exception('Min value greater than max.');
                }

                if ( ($value >= $min) && ($value <= $max) ){
                    return true;
                }else{
                    if ($value < $min){
                        $this->_addMessage(self::TOO_SHORT);
                        return false;
                    }elseif(strlen($value) > $max){
                        $this->_addMessage(self::TOO_LONG);
                        return false;
                    }
                }
            }

            // Is min only set?
            if (is_numeric($min)){
                if ($value < $min){
                    $this->_addMessage(self::TOO_SHORT);
                    return false;
                }else{
                    return true;
                }
            }

            // Is max only set?
            if (is_numeric($max)){
                if ($value > $max){
                    $this->_addMessage(self::TOO_LONG);
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