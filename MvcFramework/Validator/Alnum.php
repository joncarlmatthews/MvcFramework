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
     * Alnum validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class Alnum extends ValidatorAbstract
    {
        /**
         * Not almum error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const ERROR_ALNUM = 'notAlnum';

        /**
         * Not almum (with space) error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const ERROR_ALNUM_SPACE = 'notAlnumSpace';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::ERROR_ALNUM           => 'String can only contain letters and numbers',
            self::ERROR_ALNUM_SPACE     => 'String can only contain letters, numbers and spaces',
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
            if ( (isset($this->_options['allowWhiteSpace'])) && (true == $this->_options['allowWhiteSpace']) ){
                if(preg_match('/[^0-9a-zA-Z\s]/', $value)){
                    $this->_addMessage(self::ERROR_ALNUM_SPACE);
                    return false;
                }else{
                    return true;
                }
            }else{

                if(preg_match('/[^0-9a-zA-Z]/', $value)){
                    $this->_addMessage(self::ERROR_ALNUM);
                    return false;
                }else{
                    return true;
                }
            }
        }
    }
}