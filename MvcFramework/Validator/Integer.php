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
     * Is integer validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class Integer extends ValidatorAbstract
    {
        /**
         * Not int error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const IS_NOT_INT = 'isNotInt';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::IS_NOT_INT => 'Value is not an int',
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
            $res = is_int($value);

            if (!$res){
                $this->_addMessage(self::IS_NOT_INT);
                return false;
            }else{
                return true;
            }
        }
    }
}