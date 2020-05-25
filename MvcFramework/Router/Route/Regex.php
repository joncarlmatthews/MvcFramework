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
    use MvcFramework\Router\Route as Route;
    
    /**
     * Regex Route class.
     *
     * The Regex class provides methods for setting routes that match a regular
     * expression.
     *
     * @category    MvcFramework
     * @package     Router_Route
     * @subpackage  Regex
     * @author      Jon Matthews
     */
    class Regex extends Route
    {
        /**
         * Checks if the current request's URI matches the route's pattern.
         *
         * @access public
         * @author Jon Matthews
         * @param string $uriPath
         * @return bool
         */
        public function match($uriPath)
        {
            $uriPath = trim($uriPath, $this->_urlPathDelimiter);
            // ...
        }
    }
}