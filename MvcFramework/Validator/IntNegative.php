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
     * Is negative integer validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class IntNegative extends ValidatorAbstract
    {
        /**
         * Not negative error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const IS_NOT_NEGATIVE = 'isNotNegative';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::IS_NOT_NEGATIVE => 'Value is not negative',
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
            $filterInt = new \MvcFramework\Filter\Integer;
            $value = $filterInt->filter($value);

            if ($value < 0){
                return true;
            }else{
                $this->_addMessage(self::IS_NOT_NEGATIVE);
                return false;
            }
        }
    }
}