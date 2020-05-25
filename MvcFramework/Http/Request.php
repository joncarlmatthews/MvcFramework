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

namespace MvcFramework\Http
{
    /**
     * The Request class provides methods for accessing the HTTP request 
     * parameters
     *
     * @author Jon Matthews
     * @category    MvcFramework
     * @package     Http
     * @subpackage  Request
     */
    class Request
    {
        /**
         * The base path of the URL.
         *
         * @todo Create setters for this property.
         *
         * @see MvcFramework\Http\Request::getBasePath()
         * @var string
         */
        private $_basePath = '/';    

        /**
         * Incremnting number of how many attempts have been made to dispatch to 
         * an action.
         *
         * @var integer
         */
        protected $_dispatchAttempt = 0;    

        /**
         * Has the action been dispatched?
         *
         * @var boolean
         */
        protected $_dispatched = false;

        /**
         * Module name.
         *
         * @var string
         */
        private $_moduleName = null;

        /**
         * Controller name.
         *
         * @var string
         */
        private $_controllerName = null;

        /**
         * Action name.
         *
         * @var string
         */
        private $_actionName = null;

        /**
         * Request parameters
         *
         * @var array
         */
        private $_params = array();

        /**
         * Class constructor.
         *
         * @access public
         * @author  Jon Matthews
         * @return MvcFramework\Http\Request
         */
        public function __construct(){}

        /**
         * Getter for @link $_basePath
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getBasePath()
        {
            return $this->_basePath;
        }

        /**
         * Increments the dispatch attempt count.
         *
         * @access public
         * @author Jon Matthews
         * @return \MvcFramework\Http\Request
         */
        public function incrementDispatchAttemptCount()
        {
            $this->_dispatchAttempt = ($this->_dispatchAttempt + 1);
            return $this;
        }

        /**
         * Returns the dispatch attempt count.
         *
         * @access public
         * @author Jon Matthews
         * @return integer
         */
        public function getDispatchAttemptCount()
        {
            return $this->_dispatchAttempt;
        }

        /**
         * Set flag indicating whether or not the request has been fully
         * dispatched.
         *
         * @access public
         * @param boolean $flag
         */
        public function setDispatched($flag = true)
        {
            $this->_dispatched = $flag ? true : false;
            return $this;
        }

        /**
         * Determine if the request has been fully dispatched.
         *
         * @access public
         * @return boolean
         */
        public function isDispatched()
        {
            return $this->_dispatched;
        }

        /**
         * Set the module name to use.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $value
         * @return \MvcFramework\Http\Request
         */
        public function setModuleName($value)
        {
            $this->_moduleName = $value;
            return $this;
        }

        /**
         * Retrieve the module name.
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
         * Set the controller name to use.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $value
         * @return \MvcFramework\Http\Request
         */
        public function setControllerName($value)
        {
            $this->_controllerName = $value;
            return $this;
        }

        /**
         * Retrieve the controller name.
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
         * Set the action name.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $value
         * @return \MvcFramework\Http\Request
         */
        public function setActionName($value)
        {
            $this->_actionName = $value;
            return $this;
        }

        /**
         * Retrieve the action name.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getActionName($withSuffix = true)
        {
            $withSuffix = (bool)$withSuffix;

            $actionName = $this->_actionName;

            if (!$withSuffix){
                $actionName = str_replace('Action', null, $actionName);
            }

            return $actionName;
        }

        /**
         * Adds a key/value into the request parameters.
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $key
         * @param   value   $value
         * @return \MvcFramework\Http\Request
         */
        public function setParam($key, $value)
        {
            $key = (string)$key;

            if ((null === $value) && isset($this->_params[$key])) {
                unset($this->_params[$key]);
            } elseif (null !== $value) {
                $this->_params[$key] = $value;
            }

            return $this;
        }

        /**
         * Adds multiple key/value into the request parameters.
         *
         * @access public
         * @author  Jon Matthews
         * @param   array  $array
         * @return \MvcFramework\Http\Request
         */
        public function setParams(array $array)
        {
            $this->_params = $this->_params + (array) $array;

            foreach ($array as $key => $value) {
                if (null === $value) {
                    unset($this->_params[$key]);
                }
            }

            return $this;
        }

        /**
         * Returns a request parameter.
         *
         * @access public
         * @author  Jon Matthews
         * @param   string  $key
         * @param   mixed  $default
         * @return  mixed
         */
        public function getParam($key, $default = null)
        {
            $key = (string) $key;
            if (isset($this->_params[$key])) {
                return $this->_params[$key];
            }

            return $default;
        }

        /**
         * Returns all request parameters.
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
         * Returns the request URI value.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getUri()
        {
            return $_SERVER['REQUEST_URI'];
        }

        /**
         * Returns the server's request method.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getMethod()
        {
            return $_SERVER['REQUEST_METHOD'];
        }

        /**
         * Returns the server's protocol.
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getProtocol()
        {
            return $_SERVER['SERVER_PROTOCOL'];
        }


        /**
         * Was the request made by POST?
         *
         * @access public
         * @author  Jon Matthews
         * @return boolean
         */
        public function isPost()
        {
            if ('POST' == $this->getMethod()) {
                return true;
            }

            return false;
        }

        /**
         * Was the request made by GET?
         *
         * @access public
         * @author  Jon Matthews
         * @return boolean
         */
        public function isGet()
        {
            if ('GET' == $this->getMethod()) {
                return true;
            }

            return false;
        }

        /**
         * Was the request made by PUT?
         *
         * @access public
         * @author  Jon Matthews
         * @return boolean
         */
        public function isPut()
        {
            if ('PUT' == $this->getMethod()) {
                return true;
            }

            return false;
        }

        /**
         * Was the request made by DELETE?
         *
         * @access public
         * @author  Jon Matthews
         * @return boolean
         */
        public function isDelete()
        {
            if ('DELETE' == $this->getMethod()) {
                return true;
            }

            return false;
        }

        /**
         * Retrieve a member of the $_GET superglobal
         *
         * If no $key is passed, returns the entire $_GET array.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $key
         * @param mixed $default Default value to use if key not found
         * @return mixed Returns null if key does not exist
         */
        public function getQuery($key = null, $default = null)
        {
            if (null === $key) {
                return $_GET;
            }

            return (isset($_GET[$key])) ? $_GET[$key] : $default;
        }

        /**
         * Retrieve a member of the $_POST superglobal
         *
         * If no $key is passed, returns the entire $_POST array.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $key
         * @param mixed $default Default value to use if key not found
         * @return mixed Returns null if key does not exist
         */
        public function getPost($key = null, $default = null)
        {
            if (null === $key) {
                return $_POST;
            }

            return (isset($_POST[$key])) ? $_POST[$key] : $default;
        }

        /**
         * Retrieve a member of the $_COOKIE superglobal
         *
         * If no $key is passed, returns the entire $_COOKIE array.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $key
         * @param mixed $default Default value to use if key not found
         * @return mixed Returns null if key does not exist
         */
        public function getCookie($key = null, $default = null)
        {
            if (null === $key) {
                return $_COOKIE;
            }

            return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
        }
    }
}