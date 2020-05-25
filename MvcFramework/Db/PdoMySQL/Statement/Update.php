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
     * The Update class provides methods for constructing a PDO MySQL UPDATE
     * statment.
     *
     * @category    MvcFramework
     * @package     Db_PdoMySQL
     * @subpackage  MySQL
     * @author      Jon Matthews
     */
    class Update extends AbstractStatement
    {
        /**
         * FROM construct.
         *
         * @access private
         * @var string
         */
        private $_table = null;

        /**
         * SELECT construct.
         *
         * @access private
         * @var array
         */
        private $_fields = array();

        /**
         * WHERE construct.
         *
         * @access private
         * @var array
         */
        private $_where = array();

        /**
         * LIMIT construct.
         *
         * @access private
         * @var int
         */
        private $_limit = false;

        /**
         * Sets the table update.
         *
         * @access public
         * @param string $table
         * @param array $fields
         * @return Update
         */
        public function table($table, array $fields = array())
        {
            // Table.
            $this->_table = $table;

            // Fields
            $this->_fields = $fields;

            return $this;
        }

        /**
         * Adds an entry to the @link $_fields array
         *
         * @access public
         * @param string $key
         * @param mixed $value
         * @return Update
         */
        public function set($key, $value)
        {
            $this->_fields[$key] = $value;

            return $this;
        }

        /**
         * Sets the WHERE clause.
         *
         * @access private
         * @param mixed $args
         * @return Update
         */
        public function where()
        {
            // Get the arguments.
            $args = func_get_args();

            if (sizeof($args) < 1){
                throw new Exception('Invalid argument');
            }

            // Extract the statement
            $statement = $args[0];

            // Remove the statement from the arguments.
            unset($args[0]);

            // Reset the argument array keys.
            $args = array_values($args);

            // Does the statement have any wildcards?
            $hasWildcards = preg_match_all(self::WILDCARD_REGEX, 
                                                $statement, 
                                                $matches);

            // Replace each wildcard in turn with the matching value.
            if ($hasWildcards){

                $wildcards = $matches[0];

                foreach($wildcards as $key => $wildcard){

                    if (isset($args[$key])){

                        $valueData = $args[$key];

                        if (is_array($valueData)){
                            $value      = $valueData[0];
                            $quoteType  = $valueData[1];
                        }else{
                            $value      = $valueData;
                            $quoteType  = null;
                        }

                        $statement = preg_replace(self::WILDCARD_REGEX, 
                                                            $this->quote($value, $quoteType),
                                                            $statement, 
                                                            1);
                    }
                }
            }

            // Add the statement to the where clauses.
            $this->_where[] = $statement;

            return $this;
        }

        /**
         * Sets the LIMIT value.
         *
         * @access private
         * @param int $value
         * @return Delete
         */
        public function limit($value)
        {
            $this->_limit = (int)$value;
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
            /*
            UPDATE `table_name`
            SET `column1` = 'value', `column2` = 'value2'
            WHERE `some_column` = 'some_value'
            LIMIT 2
             */

            // UPDATE
            $stmt  = null;
            $stmt .= 'UPDATE `' . $this->_table . '`';
            $stmt .= "\n";

            // SET
            if (empty($this->_fields)){
                throw new Exception('No SET fields defined!');
            }

            $stmt .= 'SET ';
            $i = 0;
            foreach ($this->_fields as $field => $value){

                $stmt .= '`' . $field . '` = ';

                if($value instanceof Expr){
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
            $stmt .= "\n";


            // WHERE
            $i = 0;
            foreach($this->_where as $statement){

                if (0 == $i){
                    $stmt .= 'WHERE ';
                }else{
                    $stmt .= 'AND ';
                }

                $stmt .=  '(' . $statement . ') ';

                $stmt .= "\n";

                $i++;
            }

            // LIMIT
            if ($this->_limit){
                $stmt .= 'LIMIT ' . $this->_limit . ' ';
            }

            $stmt .= ";";

            // Return the statement.
            return $stmt;
        }
    }
}