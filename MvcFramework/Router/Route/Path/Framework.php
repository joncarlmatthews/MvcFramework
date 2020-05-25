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
     * The Framework Route Path provides to attributes required to construct  
     * the standard route path. E.g /module/controller/action/param/key/foo/bar
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Router_Route
     * @subpackage  Path
     */
    class Framework extends PathAbstract
    {
        /**
         * The URL's path delimiter.
         *
         * @access protected
         * @var string
         */
        protected $_urlPathDelimiter = '/';

        /**
         * The URL's root path.
         *
         * @access protected
         * @var string
         */
        protected $_urlRootPath = '/';

        /**
         * The URL's parts delimiter.
         *
         * @access protected
         * @var string
         */
        protected $_urlPartsDelimiter = null;

        /**
         * The URL's parameter glue.
         *
         * @access protected
         * @var string
         */
        protected $_urlParamGlue = '/';

        /**
         * The URL's parameter delimiter.
         *
         * @access protected
         * @var string
         */
        protected $_urlParamDelimiter = '/';
    }
}