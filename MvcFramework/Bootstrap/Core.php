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

namespace MvcFramework\Bootstrap
{
    /**
     * Core class.
     *
     * The Core class provides methods for the initial startup and running
     * of a MvcFramework application. It checks we have the required
     * definitions and PHP modules (TODO)
     *
     * @author  Jon Matthews
     * @category   MvcFramework
     * @package    Mvc
     */
    class Core
    {
        /**
         * Core instance.
         *
         * @static
         * @access private
         */
        static private $_instance = null;

        /**
         * The Bootstrap object.
         *
         * @access private
         * @var \MvcFramework\Bootstrap\BootstrapAbstract
         */
        private $_bootstrap = null;

        /**
         * Flag for whether or not a View file has been rendered. Handy for 
         * checking before deciding whether or not to render another View file.
         *
         * @access private
         * @var Boolean
         */
        private $_viewRendered = false;

        /**
         * Private constructor for singleton
         *
         * @access private
         * @author  Jon Matthews
         * @return \MvcFramework\Core
         */
        private function __construct(){}

        /**
         * The _setInstance method provides methods for setting the singleton
         * object. 
         *
         * @static
         * @access private
         * @author  Jon Matthews
         * @return void
         */
        static private function _setInstance()
        {
            if (null == self::$_instance) {
                self::$_instance = new self;
            }
        }

        /**
         * The getInstance method provides methods for retrieving the singleton
         * object. 
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\Bootstrap\Core
         */
        static public function getInstance()
        {
            self::_setInstance();

            return self::$_instance;
        }

        /**
         * Setter for @link $_bootstrap. Set this before calling Core::run()
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @param \MvcFramework\Bootstrap\BootstrapAbstract $bootstrap
         * @return void
         */
        static public function setBootstrap(\MvcFramework\Bootstrap\BootstrapAbstract $bootstrap)
        {
            $core = self::getInstance();

            if ($core->_bootstrap instanceof \MvcFramework\Bootstrap\BootstrapAbstract){
                die('Bootstrap already set. You can only set one Bootstrap object.');
            }

            // Set the Bootstrap object.
            $core->_bootstrap = $bootstrap;

            // Initialise the application's Bootstrap object.
            $core->_bootstrap->init();

            // Call the postBootstrapInit Plugin hooks.
            foreach($core->_bootstrap->getPlugins() as $plugin){
                $plugin->postBootstrapInit();
            }
        }

        /**
         * The getBootstrap method provides global access to the application's
         * bootstrap object.
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @return MvcFramework\Bootstrap\BootstrapAbstract
         */
        static public function getBootstrap()
        {
            $core = self::getInstance();
            return $core->_bootstrap;
        }

        /**
         * Set whether or not the View file has been rendered
         *
         * @access public
         * @author  Jon Matthews
         * @return void
         */
        public function setViewRendered($flag)
        {
            $flag = (bool)$flag;
            $this->_viewRendered = $flag;
        }

        /**
         * Check whether or not the View file has been rendered
         *
         * @access public
         * @author  Jon Matthews
         * @return bool
         */
        public function getViewRendered()
        {
            return $this->_viewRendered;
        }

        /**
         * The run method runs the MvcFramework application.
         *
         * @access public
         * @author  Jon Matthews
         * @return void
         */
        public function run()
        {
            $core = self::getInstance();

            // Ensure we have a Bootstrap class to run.
            if (!$core->_bootstrap instanceof \MvcFramework\Bootstrap\BootstrapAbstract){
                die('No Bootstrap class to run. Set with Core::setBootstrap()');
            }
            
            // Ensure we have the 3 core constants definded befoe we run the
            // application.
            if (!defined('APP_NAMESPACE')){
                die('APP_NAMESPACE not defined');
            }
            if (!defined('APP_PATH')){
                die('APP_PATH not defined');
            }
            if (!defined('APP_ENV')){
                die('APP_ENV not defined');
            }
            
            // Run the application's Bootstrap instance.
            $core->_bootstrap->run();

            // Call the postDispatch Plugin hooks.
            foreach($core->_bootstrap->getPlugins() as $plugin){
                $plugin->postDispatch();
            }
        }
    }
}