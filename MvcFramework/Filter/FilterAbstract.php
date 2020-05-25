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

namespace MvcFramework\Filter
{
    use \MvcFramework\Filter\Exception;

    /**
     * The FilterAbstract class provides a Filter abstract base class for 
     * extension.
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  FilterAbstract
     */
    abstract class FilterAbstract implements FilterInterface
    {
        /**
         * An array of key/value options.
         *
         * @access protected
         * @var array
         */
        protected $_options = array();
        
        /**
         * Class constructor. Sets options to @link $_options 
         *
         * @access public
         * @author Jon Matthews
         * @param $options array
         * @return FilterAbstract
         */
        public function __construct(array $options = array())
        {
            $this->_options = $options;
        }

        /**
         * Adds an option to @link $_options 
         *
         * @access public
         * @author Jon Matthews
         * @param $key string
         * @param $value mixed
         * @return FilterAbstract
         */
        public function addOption($key, $value)
        {
            $this->_options[$key] = $value;

            return $this;
        }
    }
}