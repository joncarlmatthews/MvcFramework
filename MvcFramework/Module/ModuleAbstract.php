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

namespace MvcFramework\Module
{
    /**
     * The abstract ModuleAbstract class provides a base Module class for 
     * extension.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package Module
     */
    abstract class ModuleAbstract implements ModuleInterface
    {
        /**
         * The name of the Module.
         *
         * @access protected
         * @var string
         */
        protected $_name = null;

        /**
         * Class Constructor.
         *
         * @uses ModuleAbstract::_setName()
         *
         * @access public
         * @author Jon Matthews
         * @param string $name Name of the module.
         * @return \MvcFramework\Module\Module\ModuleAbstract
         */
        public function __construct($name)
        {
            $this->_setName($name);
        }

        /**
         * Setter for @link $_name
         *
         * @access public
         * @author Jon Matthews
         * @param string $name Name of the module.
         * @return \MvcFramework\Module\Module\ModuleAbstract
         */
        private function _setName($name)
        {
            $this->_name = ucfirst($name);
            return $this;
        }

        /**
         * Getter for @link $_name
         *
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }
    }
}