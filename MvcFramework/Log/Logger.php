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

namespace MvcFramework\Log
{
    /**
     * The Log class provides access for logging messages.
     *
     * @category    MvcFramework
     * @package     Log
     * @subpackage  Logger
     * @author      Jon Matthews
     */
    class Logger
    {
        /**
         * The Log Writter object.
         *
         * @access private
         * @var \MvcFramework\Log\Writter\WritterAbstract
         */
        private $_writter;

        /**
         * Class Constructor. Sets @link $_writter.
         *
         * @access public
         * @author  Jon Matthews
         * @param \MvcFramework\Log\Writter\WritterAbstract $writter
         * @return \MvcFramework\Log\Log
         */
        public function __construct(\MvcFramework\Log\Writter\WritterAbstract $writter)
        {
            $this->_writter = $writter;
        }

        /**
         * Logs the message using the @link $_writter::log method.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $message
         * @return bool
         */
        public function log($message)
        {
            return $this->_writter->log($message);
        }

        /**
         * Getter for @link $_writter.
         *
         * @access public
         * @author  Jon Matthews
         * @return \MvcFramework\Log\Writter\WritterAbstract
         */
        public function getWritter()
        {
            return $this->_writter;
        }
    }
}