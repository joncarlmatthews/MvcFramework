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

namespace MvcFramework\Db\PdoMySQL\Statement
{
    /**
     * The AbstractStatement class provides a base class for MySQL PDO SQL
     * statements to extend.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package Db_PdoMySQL
     * @subpackage Statement
     */
    abstract class AbstractStatement extends \MvcFramework\Db\Statement\AbstractStatement implements StatementInterface
    {
        /**
         * Quote helper. Returns the value of PDO::quote 
         * 
         * @access public
         * @author Jon Matthews
         * @uses PDO::quote
         * @param mixed $value
         * @param string $quoteType The data type hint.
         * @return string|FALSE
         */
        public function quote($value, $quoteType = null)
        {
            if (is_null($quoteType)){
                return $this->_adapter->quote($value);
            }else{
                return $this->_adapter->quote($value, $quoteType);
            }
        }

        /**
         * Executes the query using the raw SQL.
         * 
         * @access public
         * @author Jon Matthews
         * @uses PDO::prepare
         * @uses PDOStatement::execute
         * @throws PDOException
         * @return PDOStatement
         */
        public function query()
        {
            // Fetcht the raw SQL.
            $rawSql = $this->constructSql();

            // Prepare the SQL using the PDO::prepare method.
            $sth = $this->_adapter->prepare($rawSql);

            // Execute the query.
            $sth->execute();

            // Return the PDO statement handle.
            return $sth;
        }

        /**
         * Retuns the prepared SQL from PDO::prepare using the raw SQL.
         * 
         * @access public
         * @author Jon Matthews
         * @uses PDO::prepare
         * @return PDOStatement
         */
        public function getPreparedSql()
        {
            return $this->_adapter->prepare($this->constructSql());
        }
    }
}