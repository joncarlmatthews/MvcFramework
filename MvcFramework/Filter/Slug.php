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

namespace MvcFramework\Filter
{
    /**
     * The Slug class provides methods for creating a URL slug from a string.
     *
     * @category    MvcFramework
     * @package     Filter
     */
    class Slug extends \MvcFramework\Filter\FilterAbstract
    {
        private $_maxLength = 255;
        
        /**
         * Filter method.
         *
         * @access public
         * @author Jon Matthews
         * @param string $value
         * @return string
         */
        public function filter($value)
        {
            // Replace any whitespace with a hyphen.
            $value = preg_replace('/[\s]+/u', '-', $value);

            // Replace any non-unicode char with a hyphen.
            $value = preg_replace('/[^-\p{L}]+/u', '', $value);

            // Remove double hyphens.
            $value = preg_replace('/[-]{2,}/u', '', $value);

            // Lowercase the string.
            if (function_exists('mb_strtolower')) {
                $value = mb_strtolower($value, 'UTF-8');
            }else{
                $value = strtolower($value);
            }

            // Trim to max length.
            $value = trim(substr($value, 0, $this->_maxLength));

            return $value;
        }

        public function getMaxLength()
        {
            return $this->_maxLength;
        }
    }
}