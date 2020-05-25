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

namespace MvcFramework\Utility
{

    /**
     * The ArrayMethods class provides methods for dealing with common array
     * methods.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Utility
     */
    class ArrayMethods
    {
        /**
         * Private constructor for singleton
         *
         * @access private
         * @author  Jon Matthews
         * @return ArrayMethods
         */
        private function __construct(){}
        
        /**
         * Private clone for singleton
         *
         * @access private
         * @author  Jon Matthews
         * @return ArrayMethods
         */
        private function __clone(){}
        
        /**
         * Cleans an array.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return array
         */
        static public function clean($array)
        {
            return array_filter($array, function($item) {
                return !empty($item);
            });
        }
        
        /**
         * Trims an array.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return array
         */
        static public function trim($array)
        {
            return array_map(function($item) {
                return trim($item);
            }, $array);
        }
        
        /**
         * Returns the item at the first index of an array.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return mixed
         */
        static public function first($array)
        {
            if (sizeof($array) == 0){
                return null;
            }
            
            $keys = array_keys($array);
            return $array[$keys[0]];
        }
        
        /**
         * Returns the item at the last index of an array.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return mixed
         */
        static public function last($array)
        {
            if (sizeof($array) == 0)
            {
                return null;
            }
            
            $keys = array_keys($array);
            return $array[$keys[sizeof($keys) - 1]];
        }
        
        /**
         * Converts an array to an Object of type stdClass.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return stdClass
         */
        static public function toObject($array)
        {
            $result = new \stdClass();
            
            foreach ($array as $key => $value)
            {
                if (is_array($value))
                {
                    $result->{$key} = self::toObject($value);
                }
                else
                {
                    $result->{$key} = $value;
                }
            }
            
            return $result;
        }
        
        /**
         * Flattens an array
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return mixed
         */
        static public function flatten($array, $return = array())
        {
            foreach ($array as $key => $value)
            {
                if (is_array($value) || is_object($value))
                {
                    $return = self::flatten($value, $return);
                }
                else
                {
                    $return[] = $value;
                }
            }
            
            return $return;
        }
        
        /**
         * Converts an array to a query string format.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $array
         * @return string
         */
        static public function toQueryString($array)
        {
            return http_build_query(
                self::clean(
                    $array
                )
            );
        }

        /**
         * Convert a data array to a query string ready to post.
         *
         * @param  array   $data        The data array.
         * @param  string  $delimeter   Delimiter used in query string
         * @param  boolean $urlencoded  If true encode the final query string
         *
         * @return string The array as a string.
         */
        static public function toQueryStringWithOpts(array $data, 
                                                        $delimiter = '&', 
                                                        $urlencoded = false)
        {
            $queryString = '';
            $delimiterLength = strlen($delimiter);

            // Parse each value pairs and concate to query string
            foreach ($data as $name => $value)
            {   
                // Apply urlencode if it is required
                if ($urlencoded)
                {
                    $value = urlencode($value);
                }
                $queryString .= $name . '=' . $value . $delimiter;
            }

            // remove the last delimiter
            return substr($queryString, 0, -1 * $delimiterLength);
        }

    }    
}