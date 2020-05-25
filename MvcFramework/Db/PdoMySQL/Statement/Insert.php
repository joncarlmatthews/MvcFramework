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
    use \MvcFramework\Db\PdoMySQL\Statement\Exception;

    /**
     * The Insert class provides methods for constructing a PDO MySQL INSERT
     * statment.
     *
     * @category    MvcFramework
     * @package     Db_PdoMySQL
     * @subpackage  MySQL
     * @author      Jon Matthews
     */
    class Insert extends AbstractStatement
    {
        /**
         * INTO construct.
         *
         * @access private
         * @var string
         */
        private $_into = null;

        /**
         * Field bind.
         *
         * @access private
         * @var array
         */
        private $_fields = array();

        /**
         * Database name.
         *
         * @access private
         * @var string
         */
        private $_database = null;

        /**
         * Sets the INTO values.
         *
         * @access public
         * @param string $table
         * @param array $fields
         * @return Delete
         */
        public function into($table, array $fields, $database = null)
        {
            // Table.
            $this->_into = $table;

            // Fields
            $this->_fields = $fields;

            // Database name.
            $this->_database = $database;

            return $this;
        } 

        /**
         * Processes (constructs) the SQL from the class properties.
         *
         * @access public
         * @return string
         */
        public function constructSql()
        {
            // INSERT INTO Persons 
            // (FirstName, LastName, Age) 
            // VALUES 
            // ('Glenn', 'Quagmire',33);

            $stmt  = null;
            $stmt .= 'INSERT INTO ';

            if (strlen($this->_database) >= 1){
                $stmt .= '`' . $this->_database . '`.';
            }

            $stmt .= '`' . $this->_into . '`';
            $stmt .= "\n";
            $stmt .= '(';

            $i = 0;
            foreach ($this->_fields as $field => $value){

                $stmt .= '`' . $field . '`';

                if (($i + 1) == count($this->_fields)){
                    $stmt .= '';
                }else{
                    $stmt .= ', ';
                }

                $i++;
            }

            $stmt .= ') VALUES (';

            $i = 0;
            foreach ($this->_fields as $field => $value){

                if ($value instanceof Expr) {
                    $stmt .= $value;
                }else{
                    $stmt .= $this->quote($value);
                }                

                if (($i + 1) == count($this->_fields)){
                    $stmt .= '';
                }else{
                    $stmt .= ', ';
                }

                $i++;
            }

            $stmt .= ');';

            // Return the statement.
            return $stmt;
        }
    }
}