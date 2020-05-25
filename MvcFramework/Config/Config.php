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
 * This file is responsible for creating and registerting the class auto loader.
 * This file is part of the application and *not* part of the framework because 
 * the application may require specific auto loading requirements.
 */


namespace MvcFramework\Config
{
    /**
     * Configuration class.
     *
     * The Config class provides methods for the handling a nested array of 
     * key/value pairs.
     *
     * @category   MvcFramework
     * @package    Config
     */
    class Config
    {

        /**
         * Creates a \MvcFramework\Config\Config object from a nested array of 
         * key/value pairs
         * 
         * @static
         * @access public
         * @author  Jon Matthews
         * @see APP_ENV
         * @param   array  $bind    See follwing example for required format:
         *
         * <code>
         *
         *   Array
         *   (
         *       [production] => Array
         *           (
         *               [error] => Array
         *                   (
         *                       [displayExceptions] => 0
         *                   )
         *   
         *               [db] => Array
         *                   (
         *                       [adapter] => PDO_MYSQL
         *                       [params] => Array
         *                           (
         *                               [charset] => 
         *                               [host] => 
         *                               [username] => 
         *                               [password] => 
         *                               [dbname] => 
         *                           )
         *   
         *                   )
         *   
         *           )
         *   
         *       [staging] => Array
         *           (
         *               [error] => Array
         *                   (
         *                       [displayExceptions] => 1
         *                   )
         *   
         *               [db] => Array
         *                   (
         *                       [params] => Array
         *                           (
         *                               [host] => 
         *                               [username] => 
         *                               [password] => 
         *                               [dbname] => 
         *                           )
         *   
         *                   )
         *   
         *           )
         *   
         *       [development] => Array
         *           (
         *               [error] => Array
         *                   (
         *                       [displayExceptions] => 1
         *                   )
         *       
         *               [db] => Array
         *                   (
         *                       [params] => Array
         *                           (
         *                               [host] => 
         *                               [username] => 
         *                               [password] => 
         *                               [dbname] => 
         *                           )
         *   
         *                   )
         *   
         *           )
         *   
         *   )
         *   
         * <code>
         *
         * @tutorial All configuration key/value pairs should be declared within
         * the [production] block. These values can then be overwritten (if needs
         * be) within the following blocks:
         * 
         * [staging]
         * [test]
         * [development]
         * [local]
         *
         * Each of the above blocks inherits from [production]
         *
         * @return  \MvcFramework\Config\Config
         */

        static public function loadFromArray(array $bind)
        {
            // Grab the production array config values.
            $productionConfigArray = $bind['production'];

            // Grab the array config values to overwrite the production values.
            $overwriteConfigArray =  $bind[APP_ENV];

            // Merge the two arrays into a single array.
            $mergedConfigArrays = self::_mergeConfigArrays($productionConfigArray, 
                                                            $overwriteConfigArray);

            // Convert the array into an object.
            $stdObj = json_decode(json_encode($mergedConfigArrays));

            // Convert the object into a \MvcFramework\Config\Config object.
            return self::_convertToMvcFrameworkConfigObject($stdObj);
        }

        /**
         * Method for adding configuration key/values into the 
         * \MvcFramework\Config\Config object.
         *
         * @todo
         *
         * @access public
         * @author  Jon Matthews
         * @return 
         */
        public function __set($key, $value)
        {
            //...
        }

        /**
         * Returns the configuration values as a nested array.
         *
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        public function asArray()
        {
            return get_object_vars($this);
        }

        /**
         * Converts an object into an object of type \MvcFramework\Config\Config
         *
         * @static
         * @access private
         * @author  Jon Matthews
         * @return \MvcFramework\Config\Config
         */
        static private function _convertToMvcFrameworkConfigObject($instance)
        {
            $className = '\\MvcFramework\\Config\\Config';

            return unserialize(sprintf(
                'O:%d:"%s"%s',
                strlen($className),
                $className,
                strstr(strstr(serialize($instance), '"'), ':')
            ));
        }

        /**
         * Merges 2 nested arrays overwritting where necessary.
         *
         * @access private
         * @author  Jon Matthews
         * @uses func_get_args()
         * @return array
         */
        static private function _mergeConfigArrays()
        {
            $arrays = func_get_args();
            $base = array_shift($arrays);

            foreach ($arrays as $array) {
                reset($base); //important
                while (list($key, $value) = @each($array)) {
                    if (is_array($value) && @is_array($base[$key])) {
                        $base[$key] = self::_mergeConfigArrays($base[$key], $value);
                    } else {
                        $base[$key] = $value;
                    }
                }
            }

            return $base;
        }
    
    }
}