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
    
    class FileSize extends ValidatorAbstract
    {
        /**
         * Invalid size message key.
         */
        const INVALID_SIZE = 'invalidSize';
        
        /**
         * Not numeric message key.
         */
        const NOT_NUMERIC = 'notNumeric';
        
        /**
         * Unknown message key.
         */
        const UNKNOWN = 'unknown';
        
        /**
         * Error message templates.
         * @var mixed
         */
        protected $_messageTemplates = array(
            self::INVALID_SIZE      => "The uploaded photo was too large; the maximum size is %s bytes.",
            self::NOT_NUMERIC   => '%%value%% is not numeric',
            self::UNKNOWN       => 'Number length is invalid',
        );
        
        /**
         * Error message template variables.
         * @var mixed
         */
        protected $_messageVariables = array(
            'maxSize' => array('options' => 'maxSize')
        );
        
        /**
         * Constructor override to concatenate the error string
         * @param array $options 
         */
        public function __construct(array $options = array())
        {
            parent::__construct($options);
            if(isset($this->_options['maxSize'])) {
                $this->_messageTemplates[self::INVALID_SIZE] = sprintf($this->_messageTemplates[self::INVALID_SIZE], (int)$this->_options['maxSize']);
            }
        }
        
        /**
         * Checks for a valid file size
         * @param mixed $value 
         * @throws MvcFramework\Exception\Exception 
         * @return mixed
         */
        public function isValid($value)
        {
            if(isset($this->_options['maxSize'])) {
                $max = (int)$this->_options['maxSize'];
            }
            
            if(false === $max) {
                throw new Exception('No max image size set');
            }
            
            $size = filesize($value);
            
            // Is the value numeric?
            if (!is_numeric($size)){
                $this->_addMessage(self::NOT_NUMERIC);
                return false;
            }
            
            // Is max only set?
            if (is_numeric($max)){
                if ($size > $max){
                    $this->_addMessage(self::INVALID_SIZE);
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