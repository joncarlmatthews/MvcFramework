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
    
    /**
     * Not empty validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class NotEmpty extends ValidatorAbstract
    {
        /**
         * Is empty error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const VALUE_IS_EMPTY = 'isEmpty';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::VALUE_IS_EMPTY => 'Value is required and can\'t be empty',
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
            switch(gettype($value)){

                case 'boolean':

                    // Boolean.
                    if (is_bool($value) && ($value == false)) {
                        $this->_addMessage(self::VALUE_IS_EMPTY);
                        return false;
                    }else{
                        return true;
                    }

                    break;

                case 'integer':
                case 'double':

                    // Int, real number. Class 0 and 0.0 as values?
                    return true;

                    break;

                case 'string':

                    // Trim whitespace?
                    if ( (isset($this->_options['trimWhitespace'])) 
                            && (true === $this->_options['trimWhitespace']) ){

                        $filter = new \MvcFramework\Filter\TrimWhitespace;
                        $value = $filter->filter($value);

                    }

                    // String.
                    if (0 == strlen($value)) {
                        $this->_addMessage(self::VALUE_IS_EMPTY);
                        return false;
                    }else{
                        return true;
                    }

                    break;

                case 'array':

                    // Array.
                    if (array() == $value){
                        $this->_addMessage(self::VALUE_IS_EMPTY);
                        return false;
                    }else{
                        return true;
                    }

                    break;
                case 'object':
                    throw new Exception('Type "resource" not supported');
                    break;
                case 'resource':
                    throw new Exception('Type "resource" not supported');
                    break;
                case 'NULL':
                    return true;
                    break;
                case 'unknown type':
                    throw new Exception('Unknown type');
                    break;
                default:
                    throw new Exception('Type not supported');
                    break;
            }
        }
    }
}