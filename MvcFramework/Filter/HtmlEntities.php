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
     * The HtmlEntities class provides methods for filtering a string into
     * HTML safe characters
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  HtmlEntities
     */
    class HtmlEntities extends \MvcFramework\Filter\FilterAbstract
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
            $value = (string)$value;
            if (phpversion() >= '5.4.0'){
                return htmlentities($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }else{
                return htmlentities($value, ENT_QUOTES, 'UTF-8');
            }
            
        }
    }
}