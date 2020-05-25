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

namespace MvcFramework\View\Helper
{
    /**
     * The Replace view helper provides methods for string wildcard repalcement 
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class Replace extends HelperAbstract
    {
        /**
         * Performs the repalcement.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $string
         * @param array $wildcardReplacements
         * @return string
         */
        public function replace($string, 
                                    array $wildcardReplacements = array())
        {
            $replaced = $string;

            foreach($wildcardReplacements as $wildcard => $replacement){

                if (preg_match('#(' . $wildcard .')#',
                                 $replaced)){

                    $replaced = str_replace($wildcard, 
                                                $replacement,
                                                $replaced);

                }

            }

            return $replaced;
        }
    }
}