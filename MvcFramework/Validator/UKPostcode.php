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
     * Checks a string for UK postcode validity.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class UKPostcode extends ValidatorAbstract
    {
        /**
         * Invalid error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const INVALID = 'ukPostCodeInvalid';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::INVALID => 'Value is not a valid UK postcode',
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
            $postcode = strtoupper(str_replace(' ', '', $value));

            if (preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/", $postcode) 
                    || preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/", $postcode) 
                    || preg_match("/^GIR0[A-Z]{2}$/", $postcode)) {

                return true;

            }else{

                $this->_addMessage(self::INVALID);
                return false;
                
            }
        }
    }
}