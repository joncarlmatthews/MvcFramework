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
     * Is numeric validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class Numeric extends ValidatorAbstract
    {
        /**
         * Not numeric error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const IS_NOT_NUMERIC = 'isNotNumeric';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::IS_NOT_NUMERIC => 'Value is not numeric',
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
            $res = is_numeric($value);

            if (!$res){
                $this->_addMessage(self::IS_NOT_NUMERIC);
                return false;
            }else{
                return true;
            }
        }
    }
}