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
     * The Delete class provides methods for constructing a PDO MySQL DELETE
     * statment.
     *
     * @category    MvcFramework
     * @package     Db_PdoMySQL
     * @subpackage  MySQL
     * @author      Jon Matthews
     */
    class Delete extends AbstractStatement
    {
        /**
         * FROM construct.
         *
         * @access private
         * @var array
         */
        private $_from = array();

        /**
         * JOIN construct.
         *
         * @access private
         * @var array
         */
        private $_join = array();

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
         * Sets the FROM value.
         *
         * @access public
         * @param string $table
         * @return Delete
         */
        public function from($table)
        {
            if (!is_array($table)){
                $this->_from['alias'] = null;
                $this->_from['table'] = $table;
            }else{
                foreach($table as $alias => $table){
                    if (0 === $alias){
                        $alias = null;
                    }
                    $this->_from['alias'] = $alias;
                    $this->_from['table'] = $table;
                    break;
                }
            }

            if (empty($fields)){
                $this->_fields = array('*');
            }else{
                $this->_fields = $fields;
            }

            return $this;
        }

        /**
         * Sets the LEFT JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @return Delete
         */
        public function joinLeft($table, $on)
        {
            $this->_addJoin('LEFT', $table, $on);

            return $this;
        }

        /**
         * Sets the RIGHT JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @return Delete
         */
        public function joinRight($table, $on)
        {
            $this->_addJoin('RIGHT', $table, $on);

            return $this;
        }

        /**
         * Sets the INNER JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @return Delete
         */
        public function joinInner($table, $on)
        {
            $this->_addJoin('INNER', $table, $on);

            return $this;
        }

        /**
         * Sets the OUTER JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @return Delete
         */
        public function joinOuter($table, $on)
        {
            $this->_addJoin('OUTER', $table, $on);

            return $this;
        }

        /**
         * Adds the join statement to @link $_join
         *
         * @access private
         * @param string $type
         * @param string $table
         * @param string $on
         * @return void
         */
        private function _addJoin($type, $table, $on)
        {
            if (!is_array($table)){
                $alias = null;
                $table = $table;
            }else{
                foreach($table as $alias => $table){
                    if (0 === $alias){
                        $alias = null;
                    }
                    $alias = $alias;
                    $table = $table;
                    break;
                }
            }

            $this->_join[] = array('type'       => $type,
                                    'alias'     => $alias,
                                    'table'     => $table,
                                    'on'        => $on);
        }

        /**
         * Sets the WHERE clause.
         *
         * @access private
         * @param mixed $args
         * @return Delete
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

                        // Perform the replacement with quoted value.
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
            // DELETE FROM table_name WHERE some_column = some_value

            $stmt  = null;
            $stmt .= 'DELETE ';


            if ( (!is_null($this->_from['alias'])) && (strlen($this->_from['alias']) > 0) ){
                $stmt .= '`' . $this->_from['alias'] . '` ';
                $stmt .= "\n";
            }

            if (!is_null($this->_from['alias'])){
                $from = '`' . $this->_from['table'] . '` AS `' . $this->_from['alias'] . '`';
            }else{
                $from = '`' . $this->_from['table'] . '`';
            }

            $stmt .= 'FROM ' . $from . ' ';
            $stmt .= "\n";      

            // JOIN ON
            foreach($this->_join as $leftJoin){
                if (!is_null($leftJoin['alias'])){
                    $from = '`' . $leftJoin['table'] . '` AS `' . $leftJoin['alias'] . '`';
                }else{
                    $from = '`' . $leftJoin['table'] . '`';
                }
                $stmt .= $leftJoin['type'] . ' JOIN ' . $from . ' ';
                $stmt .= 'ON ' . $leftJoin['on'] . ' ';
                $stmt .= "\n";
            }

            // WHERE
            if (empty($this->_where) && (empty($this->_having))){
                throw new Exception('No WHERE or HAVING defined!');
            }

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