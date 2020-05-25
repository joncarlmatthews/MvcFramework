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
    /**
     * The RouteInterface interface provides a common interface for Route 
     * classes. 
     *
     * @category    MvcFramework
     * @package     Router
     * @subpackage  Route
     * @author      Jon Matthews
     */
    interface  RouteInterface
    {
        /**
         * Route Constructor.
         *
         * @access public
         * @author Jon Matthews
         * @param string $pattern
         * @param array $params
         * @param PathInterface $path
         * @return RouteInterface
         */
        public function __construct($routePath, 
                                        array $args, 
                                        Path\PathInterface $path);

        /**
         * Returns the route's Path object.
         *
         * @access public
         * @author  Jon Matthews
         * @return PathInterface
         */
        public function getPath();

        /**
         * Checks if the current request's URI matches the route's pattern.
         *
         * @access public
         * @author Jon Matthews
         * @param string $uriPath
         * @return bool
         */
        public function match($uriPath);
    }
}