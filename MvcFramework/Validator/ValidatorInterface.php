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
     * The ValidatorInterface interface provides a consistent interface for
     * validator classes to conform to.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    interface ValidatorInterface
    {
        /**
         * Class construction. Sets @link $_options.
         *
         * @access public
         * @author  Jon Matthews
         * @param array $options
         * @return ValidatorAbstract
         */
        public function __construct(array $options = array());
        
        /**
         * Returns true if and only if $value meets the validation requirements
         *
         * If $value fails validation, then this method returns false, and
         * getMessages() will return an array of messages that explain why the
         * validation failed.
         *
         * @param  mixed $value
         * @return boolean
         */
        public function isValid($value);

        /**
         * Returns an array of messages that explain why the most recent isValid()
         * call returned false. The array keys are validation failure message identifiers,
         * and the array values are the corresponding human-readable message strings.
         *
         * If isValid() was never called or if the most recent isValid() call
         * returned true, then this method returns an empty array.
         *
         * @return array
         */
        public function getMessages();
    }
}