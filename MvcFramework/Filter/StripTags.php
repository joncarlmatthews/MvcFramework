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
     * The StripTags class provides methods for filtering <tags> from a string.
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  StripTags
     */
    class StripTags extends \MvcFramework\Filter\FilterAbstract
    {
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
            $allowableTags = null;

            if (isset($this->_options['allowableTags'])){
                $allowableTags = $this->_options['allowableTags'];
            }

            return strip_tags($value, $allowableTags);
        }
    }
}