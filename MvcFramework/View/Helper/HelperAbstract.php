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

    /**
     * Abstract View Helper class.
     *
     * The HelperAbstract class provides a base for View Helpers to extend.
     *
     * @category   MvcFramework
     * @package    View
     * @subpackage Helper
     */
    abstract class HelperAbstract
    {
        /**
         * The View object from which the Helper was called.
         *
         * @access private
         * @var \MvcFramework\View\View
         */
        private $_view = null;

        /**
         * Constructor. Sets @link $_view.
         *
         * @access public
         * @author  Jon Matthews
         * @var \MvcFramework\View\View $view
         * @return \MvcFramework\View\View\HelperAbstract
         */
        public function __construct(\MvcFramework\View\View $view)
        {
            $this->_view = $view;
        }

        /**
         * Getter for @link $_view
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\View\View
         */
        public function getView()
        {
            if (is_null($this->_view)){
                throw new Exception('View object not set.');
            }

            return $this->_view;
        }

        /**
         * Shortcut convenience method for returning the Request object from
         * within View Helpers.
         *
         * @access public
         * @author  Jon Matthews
         * @return Request
         */
        public function getRequest()
        {
            return $this->getView()->getRequest();
        }        
    }
}