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

namespace MvcFramework\Router
{
    use \MvcFramework\Bootstrap\Core;

    /**
     * Router class
     *
     * The Router class provides methods for dealing with the setting, getting and
     * dispatching of MVC routes.
     *
     * @category   MvcFramework
     * @package    Router
     */
    class Router
    {
        /**
         * The rewrite rule query string name.
         *
         * E.g: 
         * RewriteRule ^(.*)$ index.php?KEYNAME=/$1 [QSA,NC,L]
         *
         * @access public
         * @var string
         */
        const REWRITE_RULE_QUERY_STRING = 'url';

        /**
         * The request object.
         *
         * @access private
         * @var array
         * @see \MvcFramework\Router::__construct()
         */
        private $_request = null;

        /**
         * The value of the current request's route path.
         *
         * E.g /news/ or /user/retrieve/list/page/2
         *
         * @var string
         */
        private $_routePath = null;

        /**
         * Array of registered routes.
         *
         * @access private
         * @var array
         */
        private $_routes = array();

        /**
         * The active route.
         *
         * @access private
         * @var MvcFramework\Router\Route\RouteAbstract
         */
        private $_activeRoute = null;

        /**
         * Array of protected module route names.
         *
         * @var array
         * @access private
         */
        private $_invalidModuleNames = array('core');

        /**
         * Flag for setting whether or not the preDispatch plugin hooks have
         * been called or not.
         *
         * @access private
         * @var boolean
         */
        private $_preDispatchPluginHooksCalled = false;

        /**
         * Class constructor.
         *
         * @access private
         * @author  Jon Matthews
         * @return \MvcFramework\Router
         */
        public function __construct(\MvcFramework\Http\Request $request)
        {
            $this->_request = $request;
            $this->_setRoutePath(\MvcFramework\Router\Router::REWRITE_RULE_QUERY_STRING);
        }

        /**
         * Getter for @link $_request
         *
         * @access public
         * @author  Jon Matthews
         * @return Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        /**
         * Sets the current URI value. This does not alter the SERVER's
         * REQUEST_URI value.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $apacheGetParamKey Apache's rewrite rule GET key name
         * @return Request
         */
        private function _setRoutePath($apacheGetParamKey)
        {
            // Has the Apache rewrite rule passed in a URL parameter?
            if ( ($this->getRequest()->getQuery($apacheGetParamKey)) 
                    && (strlen($this->getRequest()->getQuery($apacheGetParamKey)) >= 1) ){
                // ...yes.
                $this->_routePath = $this->getRequest()->getQuery($apacheGetParamKey);
            }else{
                // ...no, grab the route from the server's REQUEST_URI.
                $this->_routePath = $this->getRequest()->getUri();
            }
            return $this;
        }

        /**
         * Getter for @link $_routePath
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getRoutePath()
        {
            return $this->_routePath;
        }

        /**
         * Returns an array of the path parts based on the Path class passed in.
         *
         * @access public
         * @author  Jon Matthews
         * @param PathInterface The path class on whioch to determine how to
         *                       extract the path value.
         * @return array
         */
        public function getRoutePathParts(\MvcFramework\Router\Route\Path\PathInterface $path)
        {
            $pathParts = explode($path->getUrlPathDelimiter(), 
                                        $this->_stripQueryString($this->getRoutePath()));

            return array_filter($pathParts);
        }

        /**
         * Adds a route to the router.
         *
         * The first route added has the highest priority, the last route the
         * lowest.
         *
         * @access public
         * @author Jon Matthews
         * @param MvcFramework\Router\Route $route
         * @return MvcFramework\Router
         */
        public function addRoute($key, \MvcFramework\Router\Route $route)
        {
            $this->_routes[$key] = $route;

            return $this;
        }

        /**
         * Returns a registered Route object based on $key
         *
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @return RouteAbstract
         */
        public function getRoute($key)
        {
            if ($this->routeExists($key)){
                return $this->_routes[$key];
            }

            throw new Exception('Route ' . $key. ' not found', 404);
        }

        /**
         * Returns the array of registered Route objects
         *
         * @access public
         * @author Jon Matthews
         * @return array
         */
        public function getRoutes()
        {
            return $this->_routes;
        }

        /**
         * Checks whether a route exists based on $key
         *
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @return array
         */
        public function routeExists($key)
        {
            if (array_key_exists($key, $this->_routes)){
                return true;
            }
            return false;
        }

        /**
         * Tells the router which Route is active. As in, which route has matched
         * the URI.
         *
         * @access public
         * @author Jon Matthews
         * @param RouteAbstract $route
         * @return Route
         */
        public function setActiveRoute(\MvcFramework\Router\Route\RouteAbstract $route)
        {
            $this->_activeRoute = $route;
            return $this;
        }

        /**
         * Returns the active route.
         *
         * @access public
         * @author Jon Matthews
         * @return Route
         */
        public function getActiveRoute()
        {
            return $this->_activeRoute;
        }

        /**
         * Dispatches the request.
         *
         * Loops through the registered routes and runs each of the route's
         * match() method inturn. If a match is found, the request will be
         * dispatched to that route.
         *
         * @access public
         * @author Jon Matthews
         * @return void
         */
        public function dispatch()
        {
            // Get the base path value.
            $basePath = Core::getBootstrap()->getRequest()->getBasePath();

            // Get the URI. (Strip the query vars off first)
            $uri = $this->_stripQueryString(trim($this->getRequest()->getUri(), $basePath));

            // urldecode the URI to ensure UTF-8 characters arent 
            // percentage-encoded
            $uri = urldecode($uri);

            // Does the URI contain a value to check against?
            if($uri != null){

                // Yes, check the URL against any user definded routes.

                // Loop through the routes.
                foreach(array_reverse($this->_routes) as $routeName => $routeObj){

                    /*
                    echo $routeName;
                    echo '<br>';
                    */

                    // Does the route pattern match the URI?
                    if ($routeObj->match($uri)){

                        // Debug:
                        /*
                        echo 'Route matched:<br>';
                        echo 'Name: ' . $routeName . '<br>';
                        echo 'Pattern: ' . $routeObj->getPattern() . '<br>';
                        exit();
                        */                        

                        // Dispatch.
                        $this->_dispatch($routeObj);
                        return;
                    }

                    //echo '<hr>';
                }
            }

            // No route found OR the URI doesn't contain a value, so simply 
            // dispatch to the default route.
            $this->_dispatch($this->getRoute('default'));
        }

        /**
         * Dispatches the request to a specific route bypassing the route's
         * match() method.
         *
         * @access public
         * @author Jon Matthews
         * @param string $routeName The route to dispatch to.
         * @return void
         */
        public function dispatchToRoute($routeName)
        {
            $route = $this->getRoute($routeName);
            $this->_dispatch($route);
        }

        /**
         * The main body of the dispatch() method. Requires the controller class
         * and calls the controllers action method.
         *
         * @access public
         * @author Jon Matthews
         * @param RouteAbstract $route The route to dispatch to.
         * @return void
         */
        private function _dispatch(\MvcFramework\Router\Route\RouteAbstract $route)
        {

            // Increment the "dispatch attempted" count.
            $this->getRequest()->incrementDispatchAttemptCount();

            // Is the module name invalid?
            if (in_array(strtolower($route->getModuleName()), $this->_invalidModuleNames)){

                throw new Exception('Invalid Route', 404);
            }  

            // Does the module exist?
            $controllerFilePath = APP_PATH 
                                    . DIRECTORY_SEPARATOR 
                                    . APP_NAMESPACE 
                                    . DIRECTORY_SEPARATOR 
                                    . $route->getModuleName()
                                    . DIRECTORY_SEPARATOR 
                                    . 'Controller'
                                    . DIRECTORY_SEPARATOR
                                    . $route->getControllerName()
                                    . '.php'
                                    ;

            // Filter the file path.
            $filter = new \MvcFramework\Filter\UniversalizePath;

            $controllerFilePath = $filter->filter($controllerFilePath);

            // Debug:
            /*
            echo ($controllerFilePath);
            echo '<hr>';
            echo 'Module  ' . $route->getModuleName();
            echo '<br>';

            echo 'Controller  ' . $route->getControllerName();
            echo '<br>';

            echo 'Action  ' . $route->getActionName();
            echo '<hr>';
            echo 'Params:';
            echo '<pre>';
            print_r($route->getParams());
            echo '</pre>';
            exit();
            */

            if ( (is_file($controllerFilePath)) && (is_readable($controllerFilePath)) ){

                $controllerToInstantiate = '\\' 
                                            . APP_NAMESPACE 
                                            . '\\' 
                                            . $route->getModuleName() 
                                            . '\\Controller\\' 
                                            . $route->getControllerName();

                // Debug:
                /*
                echo 'Controller to instantiate  ' . $controllerToInstantiate;
                echo '<br>';
                exit();
                */
                
                if (!class_exists($controllerToInstantiate)) {

                    throw new Exception('Controller not found (' 
                                                . $controllerToInstantiate 
                                                . ')',
                                        404);
                }
                
                // Pass the module, controller and action to the request object
                // so we can reference them later. 
                $this->getRequest()->setModuleName($route->getModuleName());
                $this->getRequest()->setControllerName($route->getControllerName());
                $this->getRequest()->setActionName($route->getActionName());

                // Set up the route parameters.
                $route->setRouteURLParams();

                // Pass the router parameters into the request object's 
                // parameters so we can reference them later. But firstly
                // remove the MVC params as we have accessor methods for these.
                $params = $route->getParams();

                unset($params['module']);
                unset($params['controller']);
                unset($params['action']);
                
                $this->getRequest()->setParams($params);


                // Is the method callable?
                // Looping over methods for a case sensitive match
                // on the action name. As method_exists is case 
                // *inssentitive*
                $methodCallable = false;

                foreach (get_class_methods($controllerToInstantiate) as $methodName){

                    if ($methodName === $route->getActionName()){

                        $methodCallable = true;

                        // Plugin hook.
                        $this->_doPreDispatchHooks();                       

                        // Create a new instance of the relevant Controller, passing the
                        // request object in whilst we do so.
                        $controllerObject = new $controllerToInstantiate($this->getRequest(),
                                                                            $this);

                        // ...yes.

                        // Set the active route as a class property so it can be 
                        // referenced later if needs be.
                        $this->setActiveRoute($route);                    

                        // Call the method.
                        call_user_func(array($controllerObject, $route->getActionName()));

                        // Has the request been dispatched?
                        if (!$this->getRequest()->isDispatched()){

                            // Not yet.

                            // Call the auto render method.
                            call_user_func(array($controllerObject, 'autoRender'));

                        }

                        // Dispatched. Set the dispatched flag to TRUE.
                        $this->getRequest()->setDispatched(true);

                        break;
                    }

                } // foreach

                if (!$methodCallable){
                    throw new Exception('Action "' . $route->getActionName() 
                                            . '" not found within "' 
                                            . $controllerToInstantiate 
                                            . '"',
                                    404);
                }                    

            }else{

                throw new Exception('Following route not found:'
                                            . ' Module: "' . $route->getModuleName() . '"'
                                            . ' Controller: "' . $route->getControllerName() . '"'
                                            . ' Action: "' . $route->getActionName() . '"', 
                                        404);
            }
        }

        /**
         * Runs the preDispatch() hooks.
         *
         * @access public
         * @author Jon Matthews
         * @return void
         */
        private function _doPreDispatchHooks()
        {
            $bs = Core::getBootstrap();

            // Call the preDispatch Plugin hooks.
            if (false == $this->_preDispatchPluginHooksCalled){
                foreach($bs->getPlugins() as $plugin){
                    $plugin->preDispatch();
                }
            }

            // Set the flat to TRUE.
            $this->_preDispatchPluginHooksCalled = true;
        }

        /**
         * Strips the query strings.
         *
         * @access public
         * @author Jon Matthews
         * @return void
         */
        private function _stripQueryString($string)
        {
            return preg_replace('/\?.*/', '', $string);
        }
    }
}