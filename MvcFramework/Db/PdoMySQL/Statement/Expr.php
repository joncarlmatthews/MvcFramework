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
     * The Expr class provides a means by which to identify a PDO MySQL
     * expression.
     *
     * @category    MvcFramework
     * @package     Db_PdoMySQL
     * @subpackage  MySQL
     * @author      Jon Matthews
     */
    class Expr 
    {
        /**
         * The SQL expression.
         *
         * @access private
         * @var string.
         */
        private $_expr = null;

        /**
         * Class Constructor. Sets @link $_expr.
         *
         * @access public
         * @author Jon Matthews
         * @param string $expr
         * @return Expr
         */
        public function __construct($expr)
        {
            $this->_expr = $expr;
        }

        /**
         * Returns the expression.
         *
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function getExpr()
        {
            return $this->_expr;
        }

        /**
         * Magic method.
         * 
         * Returns the expression.
         *
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function __toString()
        {
            return $this->getExpr();
        }
    }
}