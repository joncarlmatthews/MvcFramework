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

namespace MvcFramework\Router\Route\Path
{
    /**
     * The PathInterface interface provides a consistent interface for xustom Route
     * Paths.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Router_Route
     * @subpackage  Path
     */
    interface PathInterface
    {
        /**
         * Getter for @link $_urlPathDelimiter;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlPathDelimiter();

        /**
         * Getter for @link $_urlRootPath;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlRootPath();

        /**
         * Getter for @link $_urlPartsDelimiter;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlPartsDelimiter();

        /**
         * Getter for @link $_urlParamGlue;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlParamGlue();

        /**
         * Getter for @link $_urlParamDelimiter;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlParamDelimiter();
    }
}