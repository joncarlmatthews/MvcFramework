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

namespace MvcFramework\Registry
{
    use \MvcFramework\Exception\Exception;

    /**
     * Registry class
     *
     * The Registry class provides methods for creating a simple registry with
     * setters and getters
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Registry
     */
    class Registry
    {
        /**
         * Registry instance.
         *
         * @static
         * @access private
         * @var \MvcFramework\Registry
         * @see \MvcFramework\Registry\Registry::getInstance()
         */
        static private $_instance = null;

        /**
         * Array of registry objects.
         *
         * @access private
         * @var array
         * @see \MvcFramework\Registry\Registry::set()
         * @see \MvcFramework\Registry\Registry::get()
         */
        private $_bind = array();

        /**
         * Flag for whether registry objects can be overwritten.
         *
         * @access private
         * @var boolean
         */
        private $_canOverwrite = false;

        /**
         * Private constructor for singleton.
         *
         * @access private
         * @author  Jon Matthews
         * @return \MvcFramework\Registry
         */
        private function __construct(){}

        /**
         * The _setInstance method provides methods for setting the singleton
         * object. 
         *
         * @access private
         * @author  Jon Matthews
         * @return void
         */
        static private function _setInstance()
        {
            if (null == self::$_instance) {
                self::$_instance = new self;
            }
        }

        /**
         * The getInstance method provides methods for retrieving the singleton
         * object. 
         *
         * @access private
         * @author  Jon Matthews
         * @return \MvcFramework\Registry
         */
        static public function getInstance()
        {
            self::_setInstance();

            return self::$_instance;
        }

        /**
         * The set method provides methods for setting objects into the
         * registry. 
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @param   string  $key
         * @param   mixed   $value
         * @return void
         */
        static public function set($key, $value)
        {
            $registry = self::getInstance();

            if ($registry->_canOverwrite){
                $registry->_bind[$key] = $value;
            }else{
                if (!array_key_exists($key, $registry->_bind)){
                    $registry->_bind[$key] = $value;
                }else{
                    throw new Exception('Cannot overwrite registry 
                                                                key ' . $key);
                }
            }
        }

        /**
         * The get method provides methods for returning objects from the
         * registry. 
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @param   string  $key
         * @return mixed
         */
        static public function get($key)
        {
            $registry = self::getInstance();

            if (array_key_exists($key, $registry->_bind)){
                return $registry->_bind[$key];
            }
            
            throw new Exception('No Registry entry for key "' . $key . '"');
        }

        /**
         * Checks to see if $key exists within the registry.
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @param   string  $key
         * @return  bool
         */
        static public function exists($key)
        {
            $registry = self::getInstance();
            
            if (array_key_exists($key, $registry->_bind)){
                return true;
            }else{
                return false;
            }
        }

        /**
         * Setter for @link $_canOverwrite
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @param   boolean  $flag
         * @return void
         */
        static public function setCanOverwrite($flag)
        {
            $flag = (bool)$flag;

            $registry = self::getInstance();
            $registry->_canOverwrite = $flag;
        }

        /**
         * Getting for @link $_canOverwrite
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @return boolean
         */
        static public function getCanOverwrite()
        {
            $registry = self::getInstance();
            
            return $registry->_canOverwrite;
        }

        /**
         * Getting for @link $_bind
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        static public function getAll()
        {
            $registry = self::getInstance();

            return $registry->_bind;
        }

        /**
         * Getting for all @link $_bind keys
         *
         * @static
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        static public function getAllKeys()
        {
            $registry = self::getInstance();

            return array_keys($registry->_bind);
        }
    }
}