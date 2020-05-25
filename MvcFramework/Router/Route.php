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
    /**
     * The Route class is the standard route class for the framework.
     *
     * @category    MvcFramework
     * @package     Router
     * @author      Jon Matthews
     */
    class Route extends Route\RouteAbstract implements Route\RouteInterface
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
            $uriPath = ltrim($uriPath, $this->_basePath);
            $uriPath = rtrim($uriPath, $this->_path->getUrlRootPath());

            // Are there any keys?
            preg_match_all('#' . self::URL_VARIABLE_DELIMITER . '([a-zA-Z0-9]+)#', 
                                                                    $this->getPattern(), 
                                                                    $keys);

            // Are there keys (:) in the URL?
            if( (sizeof($keys)) && (sizeof($keys[0])) && (sizeof($keys[1])) ){

                // Yes.

                // Grab the key names
                $keyNames = $keys[1];

                /*
                echo '<pre>';
                print_r($keyNames);
                echo '</pre>';
                */

                $pattern = str_replace('*', '(.*)', $this->getPattern());

                // normalize route pattern
                $normalizeDRoutePattern = preg_replace("#(" . self::URL_VARIABLE_DELIMITER . "[a-zA-Z0-9]+)#", 
                                                        "([0-9-_\p{L}]+)", 
                                                        $pattern);
                
                // Fetch the values of the keys.
                preg_match_all("#^{$normalizeDRoutePattern}$#", $uriPath, $values);


                if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])){

                    // unset the matched url
                    unset($values[0]);

                    // The matched array is multidimensional, so make it single
                    // dimensional.
                    $keyValues = \MvcFramework\Utility\ArrayMethods::flatten($values);

                    // How many keys we're defined?
                    $numberOfKeyNames = count($keyNames);

                    // How many key values do we have?
                    $numberOfKeyValues = count($keyValues);

                    // If there are more key values than key names then splice
                    // the values array to fit.
                    if ($numberOfKeyValues > $numberOfKeyNames){

                        // Make a copy of the key values before we truncate them.
                        $originalKeyValues = $keyValues;

                        array_splice($keyValues, $numberOfKeyNames);
                    }

                    // Merge the key names with the key values.
                    $derivedKeys = array_combine($keyNames, 
                                                $keyValues);

                    // Debug:
                    /*
                    echo '<pre>';
                    print_r($derivedKeys);
                    echo '</pre>';
                    */

                    // Merge the existing parameters with the keys.
                    $this->_params = array_merge($this->_params, $derivedKeys);
                    
                    return true;

                }else{
                    return false;
                }

            }else{
                
                $pattern = str_replace('*', '(.*)', $this->getPattern());

                // No keys in route pattern, return a simple match.
                return preg_match("#^{$pattern}$#", $uriPath);

            }
        }
    }
}