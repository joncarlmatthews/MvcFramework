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

namespace MvcFramework\View\Helper
{
    use \MvcFramework\Exception\Exception;
    use \MvcFramework\Bootstrap\Core;

    use \MvcFramework\Router\Router;

    /**
     * The Url class provides methods for constructing a URL from either a 
     * Route Name or from an array of parameters.
     *
     * @category   MvcFramework
     * @package    View
     * @subpackage Helper
     */
    class Url extends HelperAbstract
    {
        /**
         * URL constructor.
         *
         * @access public
         * @author  Jon Matthews
         * @param array     $params                 An array of parameters to pass 
         *                                          to the URL. You can also 
         *                                          pass in the module, controller 
         *                                          and action values here if 
         *                                          you're not specifying $routeName
         * @param string    $routeName              The name of a Route to build 
         *                                          the URL from.
         * @param bool      $clearRequestParams     Whether or not you want to 
         *                                          add the current request 
         *                                          parameters to the URL.
         * @return \MvcFramework\View\View
         */
        public function url(array $params = array(), 
                                $routeName = null,
                                $clearRequestParams = true)
        {
            // Has a route name been specified?
            if ( (is_null($routeName)) || (empty($routeName)) ){
                $routeName = 'default';
            }

            if ('default' == $routeName){

                if (isset($params['module'])){

                    if (!isset($params['controller'])){
                        $params['controller'] = 'index';
                    }

                    if (!isset($params['action'])){
                        $params['action'] = 'index';
                    }
                    
                }else{

                    $params['module'] = $this->getView()->getModule();

                    if (!isset($params['controller'])){
                        $params['controller'] = $this->getView()->getController();
                    }

                    if (!isset($params['action'])){
                        $params['action'] = $this->getView()->getAction();
                    }

                }
            }

            if (!$clearRequestParams){

                // Fetch the request parameters from the current URL.
                $currentRequestParams = $this->getView()->getRequest()->getParams();

                // Unset module, controller and action (though they shouldnt be
                // set anyway!)
                unset($currentRequestParams[Router::REWRITE_RULE_QUERY_STRING]);
                unset($currentRequestParams['module']);
                unset($currentRequestParams['controller']);
                unset($currentRequestParams['action']);

                // Merge the request params with the params passed in.
                $params = array_merge($currentRequestParams, $params);

            }

            // Fetch the route.
            $route = Core::getBootstrap()->getRouter()->getRoute($routeName);

            // Return the URL.
            return $route->constructUrl($params);
        }
    }
}