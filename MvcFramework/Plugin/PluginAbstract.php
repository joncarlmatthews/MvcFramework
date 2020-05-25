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

namespace MvcFramework\Plugin
{
    /**
     * Abstract Plugin class
     *
     * The Abstract Plugin class provides a base for Plugins to extend.
     *
     * @author Jon Matthews
     * @category   MvcFramework
     * @package    Plugin
     */
    abstract class PluginAbstract implements PluginInterface
    {
        /**
         * The Bootstrap object.
         *
         * @access protected
         * @var \MvcFramework\Bootstrap\BootstrapAbstract
         */
        protected $_bootstrap = null;

        /**
         * The Request object.
         *
         * @access protected
         * @var \MvcFramework\Http\Request
         */
        protected $_request = null;

        /**
         * Constructor. Auto sets the bootstrap and request properties.
         * 
         * @access public
         * @author Jon Matthews
         * @return PluginAbstract
         */
        public function __construct()
        {
            $this->_bootstrap = \MvcFramework\Bootstrap\Core::getBootstrap();
            $this->_request = $this->_bootstrap->getRequest();
        }

        /**
         * Getter for @link $_bootstrap.
         *
         * @access protected
         * @author  Jon Matthews
         * @return BootstrapAbstract
         */
        public function getBootstrap()
        {
            return $this->_request;
        }

        /**
         * Getter for @link $_request.
         *
         * @access protected
         * @author  Jon Matthews
         * @return Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        /**
         * Returns the bootstrap router's object.
         *
         * @access protected
         * @author  Jon Matthews
         * @return RouterAbstract
         */
        public function getRouter()
        {
            return $this->_bootstrap->getRouter();
        }

        /**
         * preBootstrapInit plugin method for optional extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        public function preBootstrapInit(){}
        
        /**
         * postBootstrapInit plugin method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        public function postBootstrapInit(){}

        /**
         * preDispatch plugin method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        public function preDispatch(){}

        /**
         * postDispatch plugin method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        public function postDispatch(){}        
    }
}