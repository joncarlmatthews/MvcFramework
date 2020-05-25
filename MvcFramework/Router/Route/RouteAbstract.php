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

namespace MvcFramework\Router\Route
{
    use \MvcFramework\Bootstrap\Core;
    use \MvcFramework\Router\Exception;

    /**
     * The RouteAbstract provides methods for basic route operations.
     *
     * Routes are viewed by the MvcFramework in the following format:
     *
     * <domain>[base path]<path>[path separator]<path>[path separator]<path>[root path][?parts delimiter]<param key>[param ]<param val>[param delimiter]
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Router
     * @subpackage  Route
     */
    abstract class RouteAbstract implements RouteInterface
    {
        /**
         * URL variable delimiter.
         *
         * @static
         * @access public
         * @var string
         */
        const URL_VARIABLE_DELIMITER = ':';

        /**
         * URL base path.
         *
         * @access protected
         * @var string
         */
        protected $_basePath = null;

        /**
         * The Path Interface object.
         *
         * @access protected
         * @var PathInterface
         */
        protected $_path = null;

        /**
         * The route pattern.
         *
         * @access protected
         * @var string
         */
        protected $_pattern = null;

        /**
         * The name of the route's module.
         *
         * @access protected
         * @var string
         */
        protected $_moduleName = null;

        /**
         * The name of the route's controller.
         *
         * @access protected
         * @var string
         */
        protected $_controllerName = null;

        /**
         * The name of the route's action.
         *
         * @access protected
         * @var string
         */
        protected $_actionName = null;

        /**
         * An array of parameters for the route.
         *
         * @access protected
         * @var string
         */
        protected $_params = array();

        /**
         * Route Constructor.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $pattern
         * @param array $params
         * @param PathInterface $path
         * @return RouteAbstract
         */
        public function __construct($pattern, array $params, Path\PathInterface $path = null)
        {
            // Set the base path.
            $this->_basePath = Core::getBootstrap()->getRequest()->getBasePath();

            if (is_null($path)){
                $path = \MvcFramework\Bootstrap\Core::getBootstrap()
                                                        ->getStandardRoutePath();
            }

            // Set the Path object.
            $this->_path = $path;            

            // Set the pattern
            $this->setPattern($pattern);          

            // Set the parameters
            foreach($params as $key => $value){

                if ('module' == $key){
                    $this->setModuleName($value);
                }
                if ('controller' == $key){
                    $this->setControllerName($value);
                }
                if ('action' == $key){
                    $this->setActionName($value);
                }

                $this->setParam($key, $value);
            }

            // Ensure we have the module, controller and action.
            if (is_null($this->_moduleName)){
                throw new \MvcFramework\Router\Exception('Module not set for route');
            }

            if (is_null($this->_controllerName)){
                throw new \MvcFramework\Router\Exception('Controller not set for route');
            }

            if (is_null($this->_actionName)){
                throw new \MvcFramework\Router\Exception('Action not set for route');
            }
        }

        /**
         * Magic __toString method allows quick access to the pattern prefixed 
         * with the base path.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function __toString()
        {
            return $this->constructUrl();
        }

        /**
         * Returns the route's Path object.
         *
         * @access public
         * @author  Jon Matthews
         * @return PathInterface
         */
        public function getPath()
        {
            return $this->_path;
        }

        /**
         * Sets the route's pattern.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $pattern
         * @return RouteAbstract
         */
        public function setPattern($pattern)
        {
            // Trim the base path from the front of the pattern.
            $pattern = ltrim($pattern, $this->_basePath);

            // Trim the root path from the end of the pattern.
            $pattern = rtrim($pattern, $this->_path->getUrlRootPath());

            // Replace /* with *
            $pattern = str_replace('/*', '*', $pattern);

            // Replace / with the Route's URL Path Delimiter value.
            $pattern = str_replace('/', 
                                    $this->_path->getUrlPathDelimiter(), 
                                    $pattern);

            $this->_pattern = $pattern;

            return $this;
        }

        /**
         * Returns the route's pattern.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getPattern()
        {
            return $this->_pattern;
        }

        /**
         * Sets the route's module name.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $name
         * @return RouteAbstract
         */
        public function setModuleName($name)
        {
            $this->_moduleName = $this->_sanitiseName($name);
            return $this;
        }

        /**
         * Gets the route's module name.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getModuleName()
        {
            return $this->_moduleName;
        }

        /**
         * Sets the route's controller name.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $name
         * @return RouteAbstract
         */
        public function setControllerName($name)
        {
            // Sanitize the value.
            $controllerName = $this->_sanitiseName($name);

            // If there are underscores present then explode the value on
            // each underscore to build out the correct Class name.
            if (preg_match('/[_]/i', $controllerName)){

                $controllerNameComps = explode('_', $controllerName);

                $controllerNameAmal = null;

                $i = 0;
                foreach($controllerNameComps as $controllerNameComp){

                    $controllerNameAmal .= null;

                    if ($i > 0){
                        $controllerNameAmal .= '\\';
                    }

                    $controllerNameAmal .= ucfirst($controllerNameComp);
                    
                    $i++;
                }

                $this->_controllerName = $controllerNameAmal;

            }else{

                $this->_controllerName = $controllerName;

            }

            return $this;
        }

        /**
         * Gets the route's controller name.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getControllerName()
        {
            return $this->_controllerName;
        }

        /**
         * Sets the route's action name.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $name
         * @return RouteAbstract
         */
        public function setActionName($name)
        {
            $this->_actionName = strtolower($this->_sanitiseName($name));
            return $this;
        }

        /**
         * Gets the route's action name.
         *
         * @access public
         * @author  Jon Matthews
         * @param bool $withSuffix Append Action suffix?
         * @return string
         */
        public function getActionName($withSuffix = true)
        {
            $withSuffix = (bool)$withSuffix;

            $actionName = $this->_actionName;

            if ($withSuffix){
                $actionName = $actionName . 'Action';
            }

            return $actionName;
        }

        /**
         * Gets the names of the route pattern's keys.
         *
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        public function getKeyNames()
        {
            $keyNames = array();

             // Are there any keys?
            preg_match_all('#' . self::URL_VARIABLE_DELIMITER . '([a-zA-Z0-9]+)#', 
                                                                    $this->getPattern(), 
                                                                    $keys);

            // Are there keys (:) in the URL?
            if( (sizeof($keys)) && (sizeof($keys[0])) && (sizeof($keys[1])) ){

                // Yes, grab the key names from the pattern.
                $keyNames = $keys[1];

            }

            return $keyNames;
        }

        /**
         * Sets a parameter into the route's parameter array.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $key
         * @param mixed $value
         * @return RouteAbstract
         */
        public function setParam($key, $value)
        {
            if (self::URL_VARIABLE_DELIMITER == substr($key, 0, 1)){
                throw new \MvcFramework\Router\Exception('Route parameter cannot
                    start with a "' . self::URL_VARIABLE_DELIMITER . '". Keys 
                    can only be specified within the route\'s pattern.');
            }

            $this->_params[$key] = $value;

            return $this;
        }

        /**
         * Returns the route's parameters.
         *
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        public function getParams()
        {
            return $this->_params;
        }

        /**
         * Merges the URL parameters contained within the route object into the
         * request's $_params array.
         *
         * For example a URL in the following format:
         * /<module>/<controller>/<action>/paramKey/paramVal/paramKey2/paramVal2/
         *
         * would convert to:
         * array('paramKey' => 'paramVal', 'paramKey2' => 'paramVal2')
         *
         * and then get merged with @link $_params
         *
         * @access public
         * @author  Jon Matthews
         * @param \MvcFramework\Router\Route\RouteAbstract $route
         * @return \MvcFramework\Http\Request
         */
        public function setRouteURLParams()
        {
            // Merge the URL request vars with the @link $_params array...

            // Firstly we need to remove the route's path from the 
            // $_SERVER['REQUEST_URI'] value. To do so we need to construct the
            // route path...

            // Start constructing the route path.
            $routePath  = null;

            // Base path.
            $routePath .= \MvcFramework\Bootstrap\Core::getBootstrap()
                                                            ->getRequest()
                                                            ->getBasePath();

            // Add the route pattern to the URL.
            $routePath .= $this->getPattern();

            // Does this route have any keys?
            $keyNames = $this->getKeyNames();

            // Get the route parameters
            $routeParams = $this->getParams();

            // Debug:
            /*
            echo 'Keys:';
            echo '<pre>';
            print_r($keyNames);
            echo '</pre>';

            echo 'Route Parameters:';
            echo '<pre>';
            print_r($routeParams);
            echo '</pre>';
            */

            // Replace the route keys with the relevant values
            // from the $routeParams array.
            if (!empty($keyNames)){

                $rawRequestUri = array_filter(explode($this->_path->getUrlPathDelimiter(), 
                                                        $_SERVER['REQUEST_URI']));

                foreach($keyNames as $keyName){

                    // Does the $routeParams array contain the required key/value?
                    if (array_key_exists($keyName, $routeParams)){

                        // Replace key with the relevant value.
                        $routePath = str_replace(':' . $keyName, 
                                                    $routeParams[$keyName], 
                                                    $routePath);

                    }
                }
            }

            // Remove a * wildcard if there is one.
            $routePath = str_replace('*', null, $routePath);

            // Debug:
            //echo $routePath;

            // Grab the REQUEST_URI.
            $requestParams = $_SERVER['REQUEST_URI'];

            // Remove the route path from the REQUEST_URI value.
            $requestParams = str_replace($routePath, null, $requestParams);

            // Trim the preceeding forward slash from the start of the 
            // url path.
            $requestParams = preg_replace('#^/#', null, $requestParams);

            // Explode the URL path using the path delimiter.
            $requestParams = array_values(explode($this->_path->getUrlPathDelimiter(), 
                                                                $requestParams));

            // Rebuild the url parameters ready to pass them to @link $_params
            $requestParamKeys = array();
            $requestParamVals = array();

            foreach($requestParams as $key => $value){

                if (in_array($key, $requestParamKeys)){
                    continue;
                }else{

                    $theNextKey = $key + 1;

                    if (array_key_exists($theNextKey, $requestParams)){

                        $requestParamVals[$value] = $requestParams[$theNextKey];

                        $requestParamKeys[] = $theNextKey;

                    }else{
                        $requestParamVals[$value] = null;
                    }
                }
            }

            // Unset the temp arrays.
            unset($requestParamKeys);
            unset($requestParams);

            // Remove module, controller and action keys as they're invalid.
            unset($requestParamVals['module']);
            unset($requestParamVals['controller']);
            unset($requestParamVals['action']);

            // Merge the arrays.
            $this->_params = array_merge($this->_params, $requestParamVals);

            // Finally add any $_GET params
            $this->_params = array_merge($this->_params, $_GET);

            return $this;
        }

        /**
         * Constructs a URL string based on the route pattern and parameters
         * passed into this method rather than the parameters held in
         * @link $_params.
         *
         * @access public
         * @author Jon Matthews
         * @param array $params
         * @param string $prefix    A string to prefix the relative path with. 
         *                          E.g. a hostname.
         * @return string
         */
        public function constructUrl(array $params = array(), $prefix = '')
        {
            // Start constructing the URL.
            $url  = null;
            $url .= Core::getBootstrap()->getRequest()->getBasePath();

            // Add the route pattern to the URL.
            $url .= $this->getPattern();

            if(preg_match('/\*$/', $url)){
                $url = str_replace('*', 
                                    $this->_path->getUrlRootPath()
                                        . '*', 
                                    $url);
            }else{
                $url .= $this->_path->getUrlRootPath();
            }

            // Debug:
            /*
            echo $url;
            echo '<br>';
            */
        
            // Does this route have any keys?
            $keyNames = $this->getKeyNames();

            // If so, then replace those keys with the relevant values
            // from the $params argument.
            if (!empty($keyNames)){

                foreach($keyNames as $keyName){

                    // Does the $params array contain the required key/value?
                    if (!array_key_exists($keyName, $params)){

                        throw new Exception('Missing route parameter
                                                            value for "' 
                                                            . $keyName 
                                                            . '" key');

                    }else{

                        // Replace key with the relevant value.
                        $varDelim = self::URL_VARIABLE_DELIMITER;
                        $pathSep = $this->_path->getUrlPathDelimiter();
                        $url = str_replace($varDelim . $keyName . $pathSep, 
                                                $params[$keyName] . $pathSep, 
                                                $url);

                        // Now unset the key/value from the params array
                        // as we no longer need it.
                        unset($params[$keyName]);
                    }
                }
            }

            // Is there a * wildcard? If so loop over the parameters
            // passed in and append them to the URL.
            if (strpos($url, '*') !== false) {

                $url = str_replace('*', null, $url);

                $i = 1;
                foreach($params as $key => $value){

                    if (1 == $i){
                        $url .= $this->_path->getUrlPartsDelimiter();
                    }

                    $url .= $key 
                                . $this->getPath()->getUrlParamGlue()
                                . $value;
                                
                    if ($i != count($params)){
                        $url .= $this->getPath()->getUrlParamDelimiter();
                    }

                    $i++;
                }
            }

            // Prefix supplied?
            if (strlen(trim($prefix)) > 1){
                $url = $prefix . $url;
            }

            // Debug:
            /*
            echo $url;
            echo '<hr />';
            exit();
            */

            // URL constructed, return.
            return $url;
        }

        /**
         * Filters a string to alpha only.
         *
         * @access private
         * @author  Jon Matthews
         * @param string $value
         * @return string
         */
        private function _sanitiseName($value)
        {
            return ucfirst(preg_replace('/[^a-zA-Z_]/i', '', $value));
        }
    }
}