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
    use \MvcFramework\Registry\Registry;
    use \MvcFramework\Router\Router;
    use \MvcFramework\Exception\Exception;

    /**
     * The BootstrapAbstract class provides a base Bootstrap class for 
     * extension.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package Bootstrap
     * @subpackage BootstrapAbstract
     */
    abstract class BootstrapAbstract implements BootstrapInterface
    {
        /**
         * The Registry object.
         *
         * @access protected
         * @var \MvcFramework\Registry
         */
        protected $_registry = null;

        /**
         * The Request object.
         *
         * @access protected
         * @var \MvcFramework\Http\Request
         */
        protected $_request = null;

        /**
         * The Router object.
         *
         * @access protected
         * @var \MvcFramework\Router
         */
        protected $_router = null;

        /**
         * The Standard route path object.
         *
         * @access protected
         * @var \MvcFramework\Plugin\PluginInterface
         */
        protected $_routePath = null;

        /**
         * An array of registered Modules.
         *
         * @access protected
         * @var array
         */
        protected $_modules = array();

        /**
         * An array of registered Plugins.
         *
         * @access protected
         * @var array
         */
        protected $_plugins = array();

        /**
         * The root directory of the child theme.
         *
         * @access private
         * @var string
         */
        private $_childThemeLocation = null;

        /**
         * Constructor. The *very first* setup methods are called here.
         *
         * This is called before init().
         * 
         * @access public
         * @author Jon Matthews
         * @return \MvcFramework\Bootstrap\BootstrapAbstract
         */
        public function __construct()
        {
            // Registry init.
            $this->_setupRegistry();

            // Config init.
            $this->_setupConfig();

            // Settings init.
            $this->_setupSettings();

            // Request init.
            $this->_setupRequest();            
        }

        /**
         * The main init method calls the various stages of bootstrapping the 
         * application.
         *
         * @access public
         * @author Jon Matthews
         * @return void
         */
        public function init()
        {
            // Fetch all class methods.
            $allMethods = get_class_methods($this);

            // Array of custom start methods to call.
            $customStartMethodsToCall = array();

            // Add custom start methods to array.
            foreach($allMethods as $methodName){
                if (preg_match('#^_start#', $methodName)){
                    $customStartMethodsToCall[] = $methodName;
                }
            }

            // Array of init methods to call by default.
            $defaultInitMethodsToCall = array('_initLog', 
                                                '_initRouter',
                                                '_initRoutePath',
                                                '_initDefaultModule',
                                                '_initModules',
                                                '_initDefaultRoute',
                                                '_initRoutes',
                                                );

            

            

            foreach($allMethods as $methodName){
                if (preg_match('#^_init#', $methodName)){
                    $customInitMethodsToCall[] = $methodName;
                }
            }

            // Debug:
            /*
            echo '<pre>';
            print_r($customStartMethodsToCall);
            echo '</pre>';
            
            echo '<pre>';
            print_r($defaultInitMethodsToCall);
            echo '</pre>';

            echo '<pre>';
            print_r($customInitMethodsToCall);
            echo '</pre>';
            */

            // Merge the default and custom methods.
            $methodsToCall = array_unique(array_merge($customStartMethodsToCall, 
                                                        $defaultInitMethodsToCall, 
                                                        $customInitMethodsToCall));

            // Debug:
            /* 
            echo 'CALLING:';
            echo '<pre>';
            print_r($methodsToCall);
            echo '</pre>';
            exit();
            */

            // Loop through and call the methods in turn.
            foreach($methodsToCall as $methodToCall){

                call_user_func(array($this, $methodToCall));

                // Call the preBootstrapInit Plugin hooks.
                if ('_startPlugins' == $methodToCall){
                    foreach($this->getPlugins() as $plugin){
                        $plugin->preBootstrapInit();
                    }
                }
            }
        }

        /**
         * Assigns the Registry object to @link $_registry.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _setupRegistry()
        {
            Registry::setCanOverwrite(true);
            $this->_registry = Registry::getInstance();
        }

        /**
         * Auto discovers the .ini file, then loads it and puts it in the
         * registry.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _setupConfig()
        {
            // Set the application's configuration path.
            $appConfPath = APP_PATH . DIRECTORY_SEPARATOR . 'config';

            // Does the configuration path exist?
            if (!is_dir($appConfPath)){
                throw new Exception('Cannot find application configuration 
                                                file "' . $appConfPath . '"');
            }

            // Set the application's core configuration file location.
            $confFileApp = $appConfPath . DIRECTORY_SEPARATOR . 'application.ini';

            // Load the configuration file reader.
            $configReader = new \MvcFramework\Config\Reader\Ini;

            // Load the configuration file contents into an array.
            $configArray = $configReader->fromFile($confFileApp);

            // Create a \MvcFramework\Config object from the config array.
            $configObj = \MvcFramework\Config\Config::loadFromArray($configArray);

            // Put the configuration object into the registry.         
            $this->_registry->set('config', $configObj);
        }

        /**
         * Set up any common settings.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _setupSettings()
        {
            // Set PHP's default timezone if it's declared within the config. 
            if (isset($this->_registry->get('config')->phpSettings->date->timezone)){

                date_default_timezone_set($this->_registry
                                                    ->get('config')
                                                    ->phpSettings
                                                    ->date
                                                    ->timezone);
            }

            if (isset($this->_registry->get('config')->contentEncoding)){

                header('Content-Encoding: ' 
                            . $this->_registry->get('config')->contentEncoding);

            }

            // display_errors directive.
            if (isset($this->_registry->get('config')->phpSettings->error->display)){
                ini_set('display_errors', $this->_registry->get('config')->phpSettings->error->display);
            }

            // log_errors directive.
            if (isset($this->_registry->get('config')->phpSettings->error->log)){
                ini_set('log_errors', $this->_registry->get('config')->phpSettings->error->log);
            }

            // error_log directive.
            if (isset($this->_registry->get('config')->phpSettings->error->log_path)){
                if (!preg_match('/^\//', $this->_registry->get('config')->phpSettings->error->log_path)){
                    $logPath = dirname(APP_PATH) 
                                . DIRECTORY_SEPARATOR
                                . $this->_registry->get('config')->phpSettings->error->log_path;
                }else{
                    $logPath = $this->_registry->get('config')->phpSettings->error->log_path;
                }
                ini_set('error_log', $logPath);
            }

            // session.name directive.
            if (isset($this->_registry->get('config')->phpSettings->session->name)){
                session_name($this->_registry->get('config')->phpSettings->session->name);
            }

            // session_save_path directive.
            if (isset($this->_registry->get('config')->phpSettings->session->save_path)){
                if (!preg_match('/^\//', $this->_registry->get('config')->phpSettings->session->save_path)){
                    $sessionSavePath = dirname(APP_PATH) 
                                        . DIRECTORY_SEPARATOR
                                        . $this->_registry->get('config')->phpSettings->session->save_path;
                }else{
                    $sessionSavePath = $this->_registry->get('config')->phpSettings->session->save_path;
                }
                session_save_path($sessionSavePath);
            }
        }

        /**
         * Request init.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _setupRequest()
        {
            $this->_request = new \MvcFramework\Http\Request;
        }

        /**
         * Instantiates the default logger.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initLog()
        {
            // Assume we're writting to file as standard. _initLog() can
            // be overwritten in the applications bootstrap if necessary.
            $logWritter = new \MvcFramework\Log\Writter\File;

            // Create the log object.
            $logger = new \MvcFramework\Log\Logger($logWritter);

            // Put the logger object into the registry.         
            $this->_registry->set('logger', $logger);
        }

        /**
         * Router init.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initRouter()
        {
            $this->_router = new Router($this->getRequest());
        }

        /**
         * Route path init.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initRoutePath()
        {
            $this->_routePath = new \MvcFramework\Router\Route\Path\Framework;
        }

        /**
         * Initialises the default module.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initDefaultModule()
        {
            $this->_addModule(new \MvcFramework\Module\Module('Index'));
        }

        /**
         * Modules init method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initModules()
        {

        }

        /**
         * Accessor method for adding a module to @link $_modules.
         *
         * @access protected
         * @author Jon Matthews
         * @param MvcFramework\Module\ModuleAbstract $module
         * @return MvcFramework\Bootstrap\BootstrapAbstract
         */
        protected function _addModule(\MvcFramework\Module\ModuleAbstract $module)
        {
            $this->_modules[$module->getName()] = $module;
            return $this;
        }

        /**
         * Getter for @link $_modules.
         *
         * @access protected
         * @author  Jon Matthews
         * @return array
         */
        public function getModules()
        {
            return $this->_modules;
        }

        /**
         * Initilises the default route.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initDefaultRoute()
        {
            $pathParts = $this->getRouter()
                            ->getRoutePathParts($this->getStandardRoutePath());

            // Module.
            if (array_key_exists(1, $pathParts)){
                $moduleName = $pathParts[1];
            }else{
                $moduleName = 'index';
            }

            // Controller.
            if (array_key_exists(2, $pathParts)){
                $controllerName = $pathParts[2];
            }else{
                $controllerName = 'index';
            }

            // Action.
            if (array_key_exists(3, $pathParts)){
                $actionName = $pathParts[3];
            }else{
                $actionName = 'index';
            }

            $route = new \MvcFramework\Router\Route(':module/:controller/:action*',
                                                array('module' => $moduleName,
                                                        'controller' => $controllerName,
                                                        'action' => $actionName),
                                                $this->getStandardRoutePath());
            $this->getRouter()->addRoute('default', $route);
        }

        /**
         * Routes init method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initRoutes()
        {

        }

        /**
         * Register Plugin method.
         *
         * @access protected
         * @author  Jon Matthews
         * @param \MvcFramework\Plugin\PluginInterface $plugin
         * @return \MvcFramework\Bootstrap\BootstrapAbstract
         */
        protected function _registerPlugin(\MvcFramework\Plugin\PluginInterface $plugin,
                                            $key = null)
        {
            if (is_null($key)){
                $key = get_class($plugin);
            }
            $this->_plugins[$key] = $plugin;
            return $this;
        }

        /**
         * Getter for @link $_plugins
         *
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        public function getPlugins()
        {
            return $this->_plugins;
        }

        /**
         * Getter for specific plugin.
         *
         * @access public
         * @author  Jon Matthews
         * @return mixed
         */
        public function getPlugin($key)
        {
            return $this->_plugins[$key];
        }

        /**
         * Getter for @link $_request.
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\Request
         */
        public function getRequest()
        {
            if (is_null($this->_request)){
                $this->_setupRequest();
            }
            return $this->_request;
        }

        /**
         * Getter for @link $_router.
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\Router
         */
        public function getRouter()
        {
            return $this->_router;
        }

        /**
         * Getter for @link $_routePath.
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\Router\Route\Path\PathInterface
         */
        public function getStandardRoutePath()
        {
            return $this->_routePath;
        }
        
        /**
         * Setter for @link $_childThemeLocation.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $path
         * @return string
         */
        public function setChildThemeLocation($path)
        {
            $this->_childThemeLocation = $path;
            return $this;
        }

        /**
         * Getter for @link $_childThemeLocation.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getChildThemeLocation()
        {
            return $this->_childThemeLocation;
        }

        /**
         * Runs the application. This method is called from 
         * MvcFramework\Bootstrap\Core::run
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        public function run()
        {
            // Is "display exceptions" turned on?
            if ($this->_registry->get('config')->error->displayExceptions){

                // ...display exceptions is on, so just run the app and don't 
                // bother catching any Exceptions.
                $this->getRouter()->dispatch();

            }else{

                // ...display exceptions is off, so run the app and cacth all of 
                // the Exceptions and decide what to do once they're caught.                
                try {

                    $this->getRouter()->dispatch();

                }catch (\Exception $e){

                    $errorRoute = new \MvcFramework\Router\Route(null, 
                                                        array('module' => 'index',
                                                                'controller' => 'error',
                                                                'action' => 'index',
                                                                'exception' => $e));
                    
                    $this->getRouter()->addRoute('error', $errorRoute);

                    $this->getRouter()->dispatchToRoute('error');
                }
            }
        }
    }
}