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

namespace MvcFramework\Controller
{
    use \MvcFramework\Registry\Registry;
    use \MvcFramework\View\View;
    
    /**
     * Abstract Controller class provides a base for Controllers to extend
     *
     * @author Jon Matthews
     * @category   MvcFramework
     * @package    Mvc
     */
    abstract class ControllerAbstract
    {
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
         * The View object.
         *
         * @access protected
         * @var MvcFramework\View\View
         */
        protected $_view = null;

        /**
         * Flag for whether the render menthod should auto render the View object.
         *
         * @access protected
         * @var boolean
         */
        protected $_noViewRenderer = false;

        /**
         * Constructor. Initialises required methods/objects. 
         *
         * @access public
         * @author  Jon Matthews
         * @param \MvcFramework\Http\Request $request
         * @param \MvcFramework\Router $router
         * @return \MvcFramework\Controller\ControllerAbstract
         */
        public function __construct(\MvcFramework\Http\Request $request,
                                        \MvcFramework\Router\Router $router)
        {
            // Set the Request object.
            $this->_request = $request;

            // Set the Router object.
            $this->_router = $router;

            // Initialise the View object.
            $this->_initViewObj();

            // Common init method for extension.
            $this->_init();

            // View init method for extension.
            $this->_initView();
        }

        /**
         * Initialises the View object.
         *
         * @access private
         * @author  Jon Matthews
         * @return void
         */
        private function _initViewObj()
        {
            // Create a View object.
            $this->_view = new \MvcFramework\View\View($this);
        }

        /**
         * Common init method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _init()
        {
            
        }

        /**
         * View init method for extension.
         *
         * @access protected
         * @author  Jon Matthews
         * @return void
         */
        protected function _initView()
        {
            
        }

        /**
         * Getter for @link $_request
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\Http\Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        /**
         * Getter for @link $_router
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
         * Getter for @link $_view
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\View
         */
        public function getView()
        {
            return $this->_view;
        }

        /**
         * TODO _forward reloads the whole stack causing issues with things
         * like session_start. This needs to be fixed.
         * 
         * Forwards the request to a different module, controller and action.
         *
         * @access protected
         * @author  Jon Matthews
         * @param   string  $action         Name of the action
         * @param   string  $controller     Name of the controller
         * @param   string  $module         Name of the module.
         * @param   array   $params         Array of URL paramerters to pass to 
         *                                  the route.
         * @return void
         */
        protected function _forward($action, 
                                        $controller = null, 
                                        $module = null, 
                                        array $params = array(),
                                        $routePath = null)
        {
            $bs = \MvcFramework\Bootstrap\Core::getBootstrap();

            if (is_null($controller)){
                $controller = $this->getRequest()->getControllerName();
            }

            if (is_null($module)){
                $module = $this->getRequest()->getModuleName();
            }

            if (is_null($routePath)){
                $routePath = $bs->getStandardRoutePath();
            }

            $mvc = array('module' => $module,
                            'controller' => $controller,
                            'action' => $action);
            
            $args = array_merge($mvc, $params);

            $forward = new \MvcFramework\Router\Route(null, $args, $routePath);
                        
            $bs->getRouter()->addRoute('_forward', $forward);

            $bs->getRouter()->dispatchToRoute('_forward');
        }

        /**
         * HTTP redirect method.
         *
         * @access protected
         * @author Jon Matthews
         * @param string $location
         * @return void
         */
        protected function _redirect($location, $code = 302)
        {
            header('Location: ' . $location, 
                        true, 
                        $code);
            exit();
        }

        /**
         * Sets the flag for auto view rendering.
         *
         * @access protected
         * @author Jon Matthews
         * @param bool $flag
         * @return void
         */
        protected function _setAutoViewRenderer($flag)
        {
            $flag = (bool)$flag;
            if (true == $flag){
                $this->_noViewRenderer = false;
            }else{
                $this->_noViewRenderer = true;
            }
        }

        /**
         * The autoRender will automatically call the View's render() method
         * after the Controller's action method has been called.
         *
         * @access public
         * @author  Jon Matthews
         * @return void|NULL
         */
        public function autoRender()
        {
            // Is auto View rendering enabled..?
            if (false == $this->_noViewRenderer){

                // ...yes.

                // Remove the "action" suffix and lowercase the action name.
                $actionName = $this->getRequest()->getActionName();
                $actionName = strtolower(substr($actionName, 
                                                    0, 
                                                    (strlen($actionName) - 6)));

                // Render the View.
                $this->_view->setModuleName($this->getRequest()->getModuleName())
                            ->setControllerName($this->getRequest()->getControllerName())
                            ->setViewName($actionName)
                            ->render();
                
            }

            return null;
        }
    }
}