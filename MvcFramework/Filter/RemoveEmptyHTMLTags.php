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
     * The RemoveEmptyHTMLTags class provides methods for filtering empty HTML
     * tags from a string.
     *
     * @category    MvcFramework
     * @package     Filter
     */
    class RemoveEmptyHTMLTags extends \MvcFramework\Filter\FilterAbstract
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
            $value = str_replace('<p>&nbsp;</p>', '', $value);

            $pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";

            $value = preg_replace($pattern, '', $value);            

            return $value;
        }
    }
}