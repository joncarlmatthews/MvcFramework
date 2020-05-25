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
     * The Select class provides methods for constructing a PDO MySQL SELECT
     * statment.
     *
     * @category    MvcFramework
     * @package     Db_PdoMySQL
     * @subpackage  MySQL
     * @author      Jon Matthews
     */
    class Select extends AbstractStatement
    {
        /**
         * DISTINCT clause.
         *
         * @access private
         * @var array
         */
        private $_distinct = false;

        /**
         * FROM construct.
         *
         * @access private
         * @var array
         */
        private $_from = null;

        /**
         * JOIN construct.
         *
         * @access private
         * @var array
         */
        private $_join = array();

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
         * OR WHERE construct.
         *
         * @access private
         * @var array
         */
        private $_orWhere = array();

        /**
         * HAVING construct.
         *
         * @access private
         * @var array
         */
        private $_having = array();

        /**
         * ORDER construct.
         *
         * @access private
         * @var array
         */
        private $_order = array();

        /**
         * GROUP BY construct.
         *
         * @access private
         * @var array
         */
        private $_group = array();

        /**
         * LIMIT construct.
         *
         * @access private
         * @var int
         */
        private $_limit = false;

        /**
         * OFFET construct.
         *
         * @access private
         * @var int
         */
        private $_offset = false;

        
        /**
         * Sets the FROM values.
         *
         * @access public
         * @param string $table
         * @param array $fields
         * @return Select
         */
        public function distinct()
        {
            $this->_distinct = true;
            return $this;
        }

        /**
         * Sets the FROM values.
         *
         * @access public
         * @param string $table
         * @param array $fields
         * @return Select
         */
        public function from($table, 
                                array $fields = null,
                                $database = null)
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

            if (is_null($fields)){
                $this->_fields = array('*');
            }else{
                $this->_fields = $fields;
            }

            $this->_from['database'] = $database;

            return $this;
        }

        /**
         * Adds fields to the SELECT clause from the table defined in
         * Select::from.
         *
         * @access public
         * @param array $fields
         * @return Select
         */
        public function fields(array $fields = null)
        {
            if (is_null($fields)){
                $this->_fields = array('*');
            }elseif(empty($fields)){
                $this->_fields = $fields;
            }else{
                foreach($fields as $alias => $field){
                    $this->_fields[$alias] = $field; 
                }
            }
            return $this;
        }

        /**
         * Sets the LEFT JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @param array $fields
         * @return Select
         */
        public function joinLeft($table, $on, array $fields = array(), $database = null)
        {
            $this->_addJoin('LEFT', $table, $on, $fields, $database);

            return $this;
        }

        /**
         * Sets the RIGHT JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @param array $fields
         * @return Select
         */
        public function joinRight($table, $on, array $fields = array(), $database = null)
        {
            $this->_addJoin('RIGHT', $table, $on, $fields, $database);

            return $this;
        }

        /**
         * Sets the INNER JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @param array $fields
         * @return Select
         */
        public function joinInner($table, $on, array $fields = array(), $database = null)
        {
            $this->_addJoin('INNER', $table, $on, $fields, $database);

            return $this;
        }

        /**
         * Sets the OUTER JOIN values.
         *
         * @access public
         * @param string $table
         * @param string $on
         * @param array $fields
         * @return Select
         */
        public function joinOuter($table, $on, array $fields = array(), $database = null)
        {
            $this->_addJoin('OUTER', $table, $on, $fields, $database);

            return $this;
        }

        /**
         * Adds the join statement to @link $_join
         *
         * @access private
         * @param string $type
         * @param string $table
         * @param string $on
         * @param array $fields
         * @return void
         */
        private function _addJoin($type, $table, $on, array $fields = array(), $database = null)
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
                                    'on'        => $on,
                                    'fields'    => $fields,
                                    'database'  => $database);
        }

        /**
         * Sets the WHERE clause.
         *
         * @access private
         * @param mixed $args
         * @return Select
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
         * Sets the OR WHERE clause.
         *
         * @access private
         * @param mixed $args
         * @return Select
         */
        public function orWhere()
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
            $this->_orWhere[] = $statement;

            return $this;
        }

        /**
         * Sets the HAVING value.
         *
         * @access public
         * @param mixed $args
         * @return Select
         */
        public function having()
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
            $this->_having[] = $statement;

            return $this;
        }

        /**
         * Sets the ORDER value.
         *
         * @access public
         * @param string $statement
         * @return Select
         */
        public function order($statement)
        {
            $this->_order[] = $statement;
            return $this;
        }

        /**
         * Sets the GROUP BY value.
         *
         * @access public
         * @param string $statement
         * @return Select
         */
        public function group($statement)
        {
            $this->_group[] = $statement;
            return $this;
        }

        /**
         * Sets the LIMIT value.
         *
         * @access public
         * @param int $value
         * @return Select
         */
        public function limit($value)
        {
            $this->_limit = (int)$value;
            return $this;
        }

        /**
         * Sets the OFFSET value.
         *
         * @access public
         * @param int $value
         * @return Select
         */
        public function offset($value)
        {
            $this->_offset = (int)$value;
            return $this;
        }

        /**
         * Resets a section of the values.
         *
         * @access public
         * @param string $part
         * @return Select
         */
        public function reset($part)
        {
            switch($part){
                case 'distinct':
                    $this->_distinct = false;
                    break;
                case 'from':
                    $this->_from = null;
                    break;
                case 'columns':
                    $this->_fields = array();
                    break;
                case 'where':
                    $this->_where = array();
                    break;
                case 'having':
                    $this->_having = array();
                    break;
                case 'order':
                    $this->_order = array();
                    break;
                case 'group':
                    $this->_group = array();
                    break;
                case 'limit':
                    $this->_limit = array();
                    break;
                case 'offset':
                    $this->_offset = array();
                    break;
            }
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
            // SELECT.
            $selectFields = array();

            foreach ($this->_fields as $field => $expr){

                if ('*' == $expr){
                    $expr = $expr;
                }elseif($expr instanceof Expr){
                    $expr = $expr;
                }else{
                    if (preg_match('#(\.)#', $expr)){
                        $exprParts = explode('.', $expr);

                        $i = 1;
                        $expr = null;
                        foreach($exprParts as $exprPart){
                            $expr .= '`' . $exprPart . '`';
                            if (count($exprParts) != $i){
                                $expr .= '.';
                            }
                            $i++;
                        }
                        
                    }else{
                        $expr = '`' . $expr . '`';
                    }
                }

                if (is_int($field)){
                    if (!is_null($this->_from['alias'])){
                        $selectFields[] = '`' . $this->_from['alias'] . '`.' . $expr . '';
                    }else{
                        $selectFields[] = $expr;
                    }
                }else{
                    $selectFields[] = $expr . ' AS ' . $field;
                }
            }

            // JOIN SELECT.
            foreach($this->_join as $joinData){
                foreach ($joinData['fields'] as $field => $expr){

                    if ('*' == $expr){
                        $expr = $expr;
                    }elseif($expr instanceof Expr){
                        $expr = $expr;
                    }else{
                        
                        if (preg_match('#(\.)#', $expr)){
                            $exprParts = explode('.', $expr);

                            $i = 1;
                            $expr = null;
                            foreach($exprParts as $exprPart){
                                $expr .= '`' . $exprPart . '`';
                                if (count($exprParts) != $i){
                                    $expr .= '.';
                                }
                                $i++;
                            }
                            
                        }else{
                            $expr = '`' . $expr . '`';
                        }
                    }

                    if (is_int($field)){
                        if (!is_null($joinData['alias'])){
                            $selectFields[] = '`' . $joinData['alias'] . '`.' . $expr . '';
                        }else{
                            $selectFields[] = $expr;
                        }
                    }else{
                        $selectFields[] = $expr . ' AS ' . $field;
                    }
                }
            }            

            $stmt  = null;

            $stmt .= 'SELECT ';
                
            if ($this->_distinct){
                $stmt .= 'DISTINCT ';
            }
                
            $stmt .= implode(', ' . "\n\t", $selectFields);
            $stmt .= "\n";
                        
            // FROM
            if (!is_null($this->_from['alias'])){
                if (is_object($this->_from['table'])) {
                    $parentClass = get_parent_class($this->_from['table']);
                    
                    if ($parentClass == 'MvcFramework\Db\PdoMySQL\Statement\AbstractStatement') {
                        $from = '('.$this->_from['table']->constructSql().') AS `' . $this->_from['alias'] . '`';;
                    }
                } else {

                    $from = null;

                    // Database.
                    if ( (!is_null($this->_from['database'])) && (strlen($this->_from['database']) >= 1) ){
                        $from .= '`' . $this->_from['database'] . '`.';
                    }

                    $from .= '`' . $this->_from['table'] . '` AS `' . $this->_from['alias'] . '`';
                }
            }else{

                $from = null;

                // Database.
                if ( (!is_null($this->_from['database'])) && (strlen($this->_from['database']) >= 1) ){
                    $from .= '`' . $this->_from['database'] . '`.';
                }

                $from .= '`' . $this->_from['table'] . '`';
            }

            $stmt .= 'FROM ' . $from . ' ';
            $stmt .= "\n";

            // JOIN ON
            foreach($this->_join as $joinData){

                $from = null;

                // Database.
                if ( (!is_null($joinData['database'])) && (strlen($joinData['database']) >= 1) ){
                    $from .= '`' . $joinData['database'] . '`.';
                }

                if (!is_null($joinData['alias'])){
                    $from .= '`' . $joinData['table'] . '` AS `' . $joinData['alias'] . '`';
                }else{
                    $from .= '`' . $joinData['table'] . '`';
                }
                $stmt .= $joinData['type'] . ' JOIN ' . $from . ' ';
                $stmt .= 'ON ' . $joinData['on'] . ' ';
                $stmt .= "\n";
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

            // OR WHERE
            $i = 0;
            foreach($this->_orWhere as $statement){

                $stmt .= 'OR ';

                $stmt .=  '(' . $statement . ') ';

                $stmt .= "\n";

                $i++;
            }

            // GROUP.
            $i = 0;
            foreach($this->_group as $group){

                if (0 == $i){
                    $stmt .= 'GROUP BY ';
                }

                $stmt .= $group;

                if (($i + 1) == count($this->_group)){
                    $stmt .= ' ';
                }else{
                    $stmt .= ', ';
                }

                $i++;
            }

            if (count($this->_group) >= 1){
                $stmt .= "\n";
            }
            
            
            // HAVING
            $i = 0;
            foreach($this->_having as $statement){

                if (0 == $i){
                    $stmt .= 'HAVING ';
                }else{
                    $stmt .= 'AND ';
                }

                $stmt .=  '(' . $statement . ') ';

                $stmt .= "\n";

                $i++;
            }

            // ORDER
            $i = 0;
            foreach($this->_order as $order){

                if (0 == $i){
                    $stmt .= 'ORDER BY ';
                }

                $stmt .= $order;

                if (($i + 1) == count($this->_order)){
                    $stmt .= ' ';
                }else{
                    $stmt .= ', ';
                }

                $i++;
            }

            if (count($this->_order) >= 1){
                $stmt .= "\n";
            }

            // LIMIT
            if ($this->_limit){
                $stmt .= 'LIMIT ' . $this->_limit . ' ';
                $stmt .= "\n";
            }

            if ($this->_offset){
                $stmt .= 'OFFSET ' . $this->_offset . ' ';
                $stmt .= "\n";
            }

            // Return the statement.
            return $stmt;
        }
    }
}