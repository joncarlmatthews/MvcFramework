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

namespace MvcFramework\Db\Statement
{
    /**
     * The AbstractStatement class provides a base class for MySQL SQL
     * statements to extend.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package Db
     * @subpackage Statement
     */
    abstract class AbstractStatement implements StatementInterface
    {
        /**
         * The database adapter.
         *
         * @access protected
         * @var MySQL
         */
        protected $_adapter = null;

        /**
         * The regular expression for wildcard matching.
         *
         * @access public
         * @var string
         */
        const WILDCARD_REGEX = '#(:[a-zA-Z]+)|(\?)#';

        /**
         * Constructor. Sets the database adapter.
         * 
         * @access public
         * @author Jon Matthews
         * @param MySQL $adapter
         * @return AbstractStatement
         */
        public function __construct(\MvcFramework\Db\DriverInterface $adapter)
        {
            $this->_adapter = $adapter;
        }

        /**
         * Returns the database adapter.
         *
         * @access public
         * @author Jon Matthews
         * @return void
         */
        public function getAdapter()
        {
            return $this->_adapter;
        }

        /**
         * Retuns the raw SQL constructed from the child class's constructSql
         * method.
         * 
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function getRawSql()
        {
            return $this->constructSql();
        }

        /**
         * Magic method. Returns the raw sql.
         * 
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function __toString()
        {
            return $this->getRawSql();
        }
    }
}