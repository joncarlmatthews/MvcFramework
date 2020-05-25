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
     * The abstract PathAbstract class provides a base for the custom Route
     * Paths to extend.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Router_Route
     * @subpackage  Path
     * @see         PathInterface
     */
    abstract class PathAbstract implements PathInterface
    {
        /**
         * Getter for @link $_urlPathDelimiter;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlPathDelimiter()
        {
            return $this->_urlPathDelimiter;
        }

        /**
         * Getter for @link $_urlRootPath;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlRootPath()
        {
            return $this->_urlRootPath;
        }

        /**
         * Getter for @link $_urlPartsDelimiter;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlPartsDelimiter()
        {
            return $this->_urlPartsDelimiter;
        }

        /**
         * Getter for @link $_urlParamGlue;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlParamGlue()
        {
            return $this->_urlParamGlue;
        }

        /**
         * Getter for @link $_urlParamDelimiter;
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUrlParamDelimiter()
        {
            return $this->_urlParamDelimiter;
        }
    }
}