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
 * Change Log:
 *  2013-12-23 [DMS@u01] Updates made to handle mutliple columns as primary ids, updates to: 
 *                       - getAll method
 *                
 *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
 *
 *                       
 *
 */

namespace MvcFramework\Model\Db\PdoMySQL
{
    use \MvcFramework\Model\Db\PdoMySQL\TableObject\Exception;
    use \MvcFramework\Registry\Registry;
    
    use \MvcFramework\Db\PdoMySQL\Statement\Select;
    use \MvcFramework\Db\PdoMySQL\Statement\Insert;
    use \MvcFramework\Db\PdoMySQL\Statement\Update;
    use \MvcFramework\Db\PdoMySQL\Statement\Delete;
    use \MvcFramework\Db\PdoMySQL\Statement\Expr;

    /**
     * Database table mapper class.
     * 
     * The TableObject class provides a "Model to database table" mapping class 
     * for Models to extend.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package Model_Db
     * @subpackage PdoMySQL
     */
    abstract class TableObject
    {
        /**
         * Constants flags for "on error" decisions.
         *
         * @static
         * @access public
         * @var integer
         */
        const ERR_EXCEPTION     = 1;
        const ERR_BOOLEAN       = 2;

        /**
         * Class property.
         * 
         * Array of database handler objects.
         *
         * @static
         * @access private
         * @var array
         */
        static private $_databaseHandlers = array();

        /**
         * Class property.
         * 
         * Array of table names.
         *
         * @static
         * @access private
         * @var array
         */
        static private $_tableNames = array();

        /**
         * Class property.
         * 
         * Array of primary column names.
         *
         * @static
         * @access private
         * @var array
         */
        static private $_primaryColumnNames = array();

        /**
         * The instance's table name.
         *
         * @access protected
         * @var string
         */
        protected $_tableName = null;

        /**
         * The instance's primary column name.
         *
         * @access protected
         * @var string
         */
        protected $_primaryColumnName = null;

        /**
         * The instance's primary ID value.
         *
         * @access protected
         * @var mixed
         */
        protected $_primaryID = null;

        /**
         * The instance's saved attributes (from the database table).
         *
         * @access protected
         * @var array
         */
        protected $_savedAttributes = array();

        /**
         * The instance's unsaved *unfiftered* attributes.
         *
         * @access protected
         * @var array
         */
        protected $_unsavedAttributes = array();

        /**
         * The instance's unsaved *filtered* (validated) attributes.
         *
         * @access protected
         * @var array
         */
        protected $_validatedUnsavedAttributes = array();

        /**
         * Multidimensional array of key/value/use-as-you-will humanly readable 
         * error messages to notify the user of any actions/requirements/notcies.
         *
         * Retrieve using getErrorMessages() 
         *
         * Reset using resetErrorMessages() 
         *
         * @access protected
         * @var array
         */
        protected $_errorMessages = array();

        /**
         * Class constructor.
         *
         * @access public
         * @author Jon Matthews
         * @param array $adapters
         * @return ObjectAbstract
         */
        public function __construct(array $adapters = array())
        {
            // Set database adapters.
            if (!empty($adapters)){
                self::setAdapters($adapters);
            }

            // Check we have at least one database adapter.
            if (!self::hasAdapters()){
                throw new Exception('No database handlers set for the "'
                . get_called_class() 
                . '" Model');
            }

            $calledClass = get_called_class();

            $this->_tableName           = $calledClass::getTableName();
            $this->_primaryColumnName   = $calledClass::getPrimaryColumnName();
        }

        /**
         * Sets a database hander with a key of $key.
         * 
         * Change Log:  
         *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @param Driver $hander
         * @return void
         */ 
        static public function setAdapter($key = 'default', 
                                            \MvcFramework\Db\PdoMySQL\Driver $hander)
        {
            $cc = get_called_class();            
            $calledClass = $cc::getHandlerKey();
            
            self::$_databaseHandlers[$calledClass][$key] = $hander;
        }

        /**
         * Returns a database hander with a key of $key.
         * 
         * Change Log:  
         *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @return \MvcFramework\Db\PdoMySQL
         */
        static public function getAdapter($key = 'default')
        {
            $cc = get_called_class();
            $calledClass = $cc::getHandlerKey();

            if (isset(self::$_databaseHandlers[$calledClass])
                    && (array_key_exists($key, self::$_databaseHandlers[$calledClass])) ){
                return self::$_databaseHandlers[$calledClass][$key];
            }else{
                throw new Exception('Database handler with key "' 
                . $key 
                . '" does not exist for Model "'
                . $calledClass . '"');
            }
        }

        /**
         * Checks whether or not @link $_databaseHandlers is empty.
         *
         * Change Log:  
         *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @return boolean
         */
        static public function hasAdapters()
        {
            $cc = get_called_class();
            $calledClass = $cc::getHandlerKey();

            if ( (!array_key_exists($calledClass, self::$_databaseHandlers)) 
                    || (empty(self::$_databaseHandlers[$calledClass])) ){
                return false;
            }else{
                return true; 
            }
        }

        /**
         * Checks whether or not a specific database handler has been set.
         * Change Log:  
         *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
         *  
         * @static
         * @access public
         * @author Jon Matthews
         * @return boolean
         */
        static public function hasAdapter($key)
        {
            $cc = get_called_class();
            $calledClass = $cc::getHandlerKey();

            if ( (isset(self::$_databaseHandlers[$calledClass])) &&
                    (array_key_exists($key, self::$_databaseHandlers[$calledClass])) ){
                return true;
            }else{
                return false; 
            }
        }

        /**
         * Sets multiple database hander with a key of $key.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param array $adapters
         * @return void
         */ 
        static public function setAdapters(array $adapters = array())
        {
            foreach($adapters as $key => $hander){
                self::setAdapter($key, $hander);
            }
        }

        /**
         * Returns all database handers.
         * Change Log:  
         *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
         *  
         * @static
         * @access public
         * @author Jon Matthews
         * @return array
         */
        static public function getAdapters()
        {
            $cc = get_called_class();
            $calledClass = $cc::getHandlerKey();

            if (isset(self::$_databaseHandlers[$calledClass])){
                return self::$_databaseHandlers[$calledClass];
            }

            return array();
        }

        /**
         * Returns a key to use for our $_databaseHandlers.
         * Change Log:  
         *  2013-12-27 [RJV@U02] Update to provide custom $_databaseHandlers key
         *  
         * @static
         * @access public
         * @author Roberto Valadez <roberto.valadez@swc.com>
         * @return array
         */
        static protected function getHandlerKey()
        {
            return get_called_class();
        }
        
        /**
         * Calculates (and returns) the table name based on the class name of the
         * model.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @return string
         */
        static public function getTableName()
        {
            $calledClass = get_called_class();

            if (!isset(self::$_tableNames[$calledClass])){

                $classParts = explode('\\', strtolower($calledClass));

                if (count($classParts) <= 3){
                    throw new Exception('Cannot calculate table name for'
                    . $calledClass);
                }

                unset($classParts[0]); // APP_NAMESPACE
                unset($classParts[1]); // Module name
                unset($classParts[2]); // Model directory

                $tableName = null;
                foreach($classParts as $classPart){
                    $tableName .= $classPart . '-';
                }

                $tableName = strtolower(preg_replace('/-$/', null, $tableName));

                self::$_tableNames[$calledClass] = $tableName;

            }

            return self::$_tableNames[$calledClass];
        }

        /**
         * Calculates (and returns) the primary column name based on the class 
         * name of the model.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @return string
         */
        static public function getPrimaryColumnName()
        {
            $calledClass = get_called_class();

            if (!isset(self::$_primaryColumnNames[$calledClass])){

                $tableName = explode('-', $calledClass::getTableName());

                $primaryColumnName = strtolower($tableName[count($tableName) -1]) . 'ID';

                self::$_primaryColumnNames[$calledClass] = $primaryColumnName;

            }

            return self::$_primaryColumnNames[$calledClass];
        }

        /**
         * Loads a single instance.
         *
         * @static
         * @access  public
         * @author  Jon Matthews
         * @param   array $params The arguments to determine which instance to load.
         * @return  MvcFramework\Model\DbTable\Object\ObjectAbstract
         */
        static public function load(array $params = array())
        {
            // Get the name of the called class.
            $calledClass = get_called_class();

            // Adapter.
            if (!isset($params['adapter'])){
                $params['adapter'] = 'default';
            }

            // Do we have one of the required parameters?
            if ( (!isset($params[$calledClass::getPrimaryColumnName()])) 
                    && (!isset($params['where'])) ){
                throw new Exception('Cannot load object without ' 
                . $calledClass::getPrimaryColumnName()
                . ' value or where clause');
            }
            
            // Build the query.
            $stmt = self::getAdapter($params['adapter'])
                        ->select()
                        ->from($calledClass::getTableName())
                        ->limit(1);

            // Primary column WHERE
            if (isset($params[$calledClass::getPrimaryColumnName()])){
                $stmt->where($calledClass::getPrimaryColumnName() . ' = ?', 
                                $params[$calledClass::getPrimaryColumnName()]);
            }

            // WHERE
            if (isset($params['where'])){
                if (!is_array($params['where'])){
                    $params['where'] = array($params['where']);
                }
                foreach($params['where'] as $field => $value){
                    if (is_int($field)){                        
                        $stmt->where($value);
                    }else{
                        $stmt->where($field . ' = ?', $value);
                    }
                }
            }

            // Debug:
            /*
            echo '<pre>';
            print_r($params);
            print_r($stmt->__toString());
            echo '</pre>';
            //exit();
             */

            // Return type.
            if (!isset($params['return'])){
                $params['return'] = 'result';
            }

            // On error decision.
            if (!isset($params['onError'])){
                $params['onError'] = self::ERR_EXCEPTION;
            }

            switch ($params['return']) {

                case 'stmt':
                    return $stmt;
                    break;

                case 'result':
                default:
                    
                    // Run the query.
                    $sth = $stmt->query();

                    // Fetch the row.
                    $row = $sth->fetch();

                    // Does the object exist?
                    if (!$row){
                        switch($params['onError']){
                            case self::ERR_EXCEPTION:
                            default:
                                throw new Exception($calledClass 
                                . ' database row not found');
                                break;
                            case self::ERR_BOOLEAN:
                                return false;
                                break;
                        }
                    }

                    // Create a new instance.
                    $object = new $calledClass;

                    // Store the primary ID value.
                    $object->_setPrimaryID($row[$calledClass::getPrimaryColumnName()]);

                    // Store the data from the database.
                    $object->_setSavedAttributes($row);
                    
                    // Return.
                    return $object;

                    break;
            }
        }

        /**
         * Returns an array of instances.
         *
         * Change Log:  
         *  2013-12-22 [DMS@U01] Update to accept multiple columns as primary ids
         * 
         * @static
         * @access  public
         * @author  Jon Matthews
         * @param   array $params The arguments to determine which instances to 
         *                        return.
         * @return  Select|array
         * 
         */
        static public function getAll(array $params = array())
        {
            // Get the name of the called class.
            $calledClass = get_called_class();

            // Adapter.
            if (!isset($params['adapter'])){
                $params['adapter'] = 'default';
            }            
            
            // Build the columns for the SELECT.
            if ( (isset($params['columns'])) && (!empty($params['columns']))){
                if (!is_array($params['columns'])){
                    $params['columns'] = (array)$params['columns'];
                }
                $columns = $params['columns'];
            }else{
                $columns = null;
            }

            // Build the SELECT statement.
            $stmt = self::getAdapter($params['adapter'])
                        ->select()
                        ->from($calledClass::getTableName(), $columns);

            // WHERE
            if (isset($params['where'])){
                if (!is_array($params['where'])){
                    $params['where'] = (array)$params['where'];
                }
                foreach($params['where'] as $field => $value){
                    if (is_int($field)){                        
                        $stmt->where($value);
                    }else{
                        $stmt->where($field . ' = ?', $value);
                    }
                }
            }

            // OR WHERE
            if (isset($params['orWhere'])){
                if (!is_array($params['orWhere'])){
                    $params['orWhere'] = (array)$params['orWhere'];
                }
                foreach($params['orWhere'] as $field => $value){
                    if (is_int($field)){                        
                        $stmt->orWhere($value);
                    }else{
                        $stmt->orWhere($field . ' = ?', $value);
                    }
                }
            }

            // GROUP
            if (isset($params['group'])){
                if (!is_array($params['group'])){
                    $params['group'] = (array)$params['group'];
                }
                foreach($params['group'] as $groupStatement){
                    $stmt->group($groupStatement);
                }
            }

            // ORDER
            if (isset($params['order'])){
                if (!is_array($params['order'])){
                    $params['order'] = (array)$params['order'];
                }
                foreach($params['order'] as $orderStatement){
                    $stmt->order($orderStatement);
                }
            }

            // LIMIT and OFFSET
            if ( (isset($params['perPage'])) && (isset($params['page'])) ){

                $limit = (int)$params['perPage'];
                $offset = (int)$params['page'];
                if(1 == $offset){
                    $offset = 0;
                }
                if ($offset >= 1){
                    $offset = (($offset -1) * $limit);
                }

                $stmt->limit($limit);
                $stmt->offset($offset);

            }else{

                if ( (isset($params['limit'])) || (isset($params['perPage'])) ){
                    $stmt->limit($params['limit']);
                }

                if (isset($params['offset'])){
                    $stmt->offset($params['offset']);
                }
            }

            // Return type.
            if (!isset($params['return'])){
                $params['return'] = 'result';
            }

            // Debug:
            /*
            echo '<pre>';
            print_r($params);
            print_r($stmt->__toString());
            echo '</pre>';
            echo '<hr>';
            //exit();
             */

            switch ($params['return']) {

                case 'stmt':
                    return $stmt;
                    break;
                
                case 'result':
                default:

                    // Run the query.
                    $sth = $stmt->query();

                    // Fetch the rows.
                    $rows = $sth->fetchAll();

                    // Array to hold the objects.
                    $base = array();

                    foreach($rows as $row){

                        // Create a new instance.
                        $object = new $calledClass;

                        // Store the primary ID value.                              @U01A
                        $primaryColumns = $calledClass::getPrimaryColumnName();
                        
                        // Check for multiple columns as primary IDs                @U01A
                        if (is_array($primaryColumns)){                            
                            $primaryIDs = array();
                            
                            // Set each primary ID in the array                     @U01A
                            foreach($primaryColumns as $primaryColumn){
                                $primaryIDs[count($primaryIDs)] = $row[$primaryColumn];
                            }     
                            
                            // Set current TableObject Primary ids as an array      @U01A
                            $object->_setPrimaryID($primaryIDs); 
                        }
                        else{
                            $object->_setPrimaryID($row[$calledClass::getPrimaryColumnName()]); 
                        }                                                

                        // Store the data from the database.
                        $object->_setSavedAttributes($row);

                        $object->_hasPrimaryID();
                        
                        $base[] = $object;

                    }                    
                    
                    return $base;
                    break;
            }
            
            
        }

        /**
         * Instance method for returning the table name.
         *
         * @access protected
         * @author Jon Matthews
         * @return string
         */
        protected function _getTableName()
        {
            return $this->_tableName;
        }

        /**
         * Instance method for returning the primary column name.
         *
         * @access protected
         * @author Jon Matthews
         * @return string
         */
        protected function _getPrimaryColumnName()
        {
            return $this->_primaryColumnName;
        }

        /**
         * Setter for @link $_unsavedAttributes
         *
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @param mixed $value
         * @return void
         */
        public function __set($key, $value)
        {
            $this->_unsavedAttributes[$key] = $value;
        }

        /**
         * Getter for the instance's attributes.
         *
         * If the instance is saved, then first searches unsaved attributes, 
         * then searches saved attributes.
         *
         * If the instance is yet to be saved then searches unsaved attributes
         * only.
         *
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @return mixed
         */
        public function __get($key)
        {
            if ($this->_hasPrimaryID()){
                if (array_key_exists($key, $this->_unsavedAttributes)) {
                    return $this->_unsavedAttributes[$key];
                }elseif (array_key_exists($key, $this->_savedAttributes)) {
                    return $this->_savedAttributes[$key];
                }
            }elseif(array_key_exists($key, $this->_unsavedAttributes)){
                return $this->_unsavedAttributes[$key];
            }
            return null;
        }

        /**
         * Checks if a attribute exists within either the unsaved or saved
         * arrays.
         *
         * @access public
         * @author Jon Matthews
         * @param string $key
         * @return boolean
         */
        public function __isset($key)
        {
            if ($this->_hasPrimaryID()){
                if (array_key_exists($key, $this->_unsavedAttributes)) {
                    return true;
                }elseif (array_key_exists($key, $this->_savedAttributes)) {
                    return true;
                }
            }elseif(array_key_exists($key, $this->_unsavedAttributes)){
                return true;
            }
            return false;
        }

        /**
         * Lazy method for returning the unsaved attributes. Merges
         * the unsaved *unvalidated* attributes with the unsaved *validated*
         * attributes. Validated attributes always take presidence over 
         * unvalidated ones during the merge.
         *
         * @access protected
         * @author Jon Matthews
         * @return array
         */
        protected function _getUnsavedAttributes()
        {
            $unsavedAttrs = array_merge($this->_unsavedAttributes, 
                                            $this->_validatedUnsavedAttributes);

            return $unsavedAttrs;      
        }

        /**
         * Getter for an entry from the @link $_validatedUnsavedAttributes 
         * array.
         *
         * @access protected
         * @author Jon Matthews
         * @param string $key       The key to return.
         * @param string $default   The value to return if $key doesnt exist.
         * @return mixed
         */
        protected function _getValidatedUnsavedAttribute($key, $default = null)
        {
            if (array_key_exists($key, $this->_validatedUnsavedAttributes)) {
                return $this->_validatedUnsavedAttributes[$key];
            }else{
                return $default;
            }            
        }

        /**
         * Returns the instance's primary ID value.
         * 
         * Getter for @link $_primaryID
         *
         * @access public
         * @author Jon Matthews
         * @return int
         */
        public function getPrimaryID()
        {
            return $this->_primaryID;
        }

        /**
         * Sets the instance's primary ID value.
         * 
         * Setter for @link $_primaryID
         *
         * @access protected
         * @author Jon Matthews
         * @return void
         */
        protected function _setPrimaryID($primaryID)
        {
            $this->_primaryID = $primaryID;
        }

        /**
         * Detemines if the current instance is saved by seeing whether or
         * not @link $_primaryID has a value.
         *
         * Change Log:  
         *  2013-12-22 [DMS@U01] Update to check for multiple columns as primary ids
         *  
         * @access protected
         * @author Jon Matthews
         * @return boolean
         */
        protected function _hasPrimaryID()
        {
            // check for null of primary id
            if (is_null($this->_primaryID))
                return false;
            
            // Is the primary ids an array                  @U01A
            if (is_array($this->_primaryID)){
                
                // Loop through Priamry IDs to validate     @U01A
                foreach($this->_primaryID as $primaryID)
                {
                    // Not a valid Priamry ID               @U01A
                    if (strlen($primaryID) == 0)
                        return false;
                }
            }
            else if (strlen($this->_primaryID) == 0){                          
                return false;
            }                                    
            
            // Return true only if nothing was unvalidated  @U01A
            return true;
        }

        /**
         * Sets the instance's saved attributes
         *
         * @access protected
         * @author Jon Matthews
         * @param array $bind
         * @return boolean
         */
        protected function _setSavedAttributes($bind)
        {
            $this->_savedAttributes = $bind;
        }

        /**
         * Gets the instance's saved attributes
         *
         * @access protected
         * @author Jon Matthews
         * @return array
         */
        public function getSavedAttributes()
        {
            return $this->_savedAttributes;
        }

        /**
         * Empties the instance's unsaved attributes
         *
         * @access protected
         * @author Jon Matthews
         * @return void
         */
        protected function _emptyUnsavedAttributes()
        {
            $this->_unsavedAttributes = array();
            $this->_validatedUnsavedAttributes = array();
        }

        /**
         * Adds an entry to the error message array.
         *
         * @access protected
         * @author Jon Matthews
         * @param string $code
         * @param mixed $message
         * @return void
         */
        protected function _addErrorMessage($code, $message)
        {
            $this->_errorMessages[$code] = $message;
        }

        /**
         * Adds multiple entries to the error message array.
         *
         * @access protected
         * @author Jon Matthews
         * @param array $messages
         * @return void
         */
        protected function _addErrorMessages(array $messages = array())
        {
            $this->_errorMessages = array_merge($this->_errorMessages, 
                                                    $messages);
        }

        /**
         * Returns the error message array.
         *
         * @access public
         * @author Jon Matthews
         * @return array
         */
        public function getErrorMessages()
        {
            return $this->_errorMessages;
        }

        /**
         * Resets (empties) the error message array.
         *
         * @access public
         * @author Jon Matthews
         * @return void
         */
        public function resetErrorMessages()
        {
            $this->_errorMessages = array();
        }

        /**
         * Determines whether or not @link $_errorMessages is empty.
         *
         * @access public
         * @author Jon Matthews
         * @return boolean
         */
        public function errorMessagesEmpty()
        {
            if (empty($this->_errorMessages)){
                return true;
            }else{
                return false;
            }
        }

        /**
         * Attempts to save the unsaved data.
         *
         * @access public
         * @author Jon Matthews
         * @param  array $params
         * @return int
         */
        public function save()
        {
            $params = @func_get_arg(0);

            if (!is_array($params)){
                $params = array();
            }

            // Adapter.
            if (!isset($params['adapter'])){
                $params['adapter'] = 'default';
            }

            // Are we updating or creating?
            if (!$this->_hasPrimaryID()){

                // Inserting.
                return $this->_insert($params);

            }else{

                // Updating.
                return $this->_update($params);
            }            
        }

        /**
         * INSERTs the unsaved data.
         *
         * Change Log:  
         *  2013-12-22 [DMS@U01] Update to insert to not pull back identity column
         *  
         * @access protected
         * @author Jon Matthews
         * @param  array $params
         * @return int
         */
        protected function _insert(array $params = array())
        {
            $bind = array();

            foreach($this->_getUnsavedAttributes() as $field => $value){
                $bind[$field] = $value;
            }

            $stmt = self::getAdapter($params['adapter'])
                            ->insert()
                            ->into($this->_getTableName(), 
                                    $bind);

            // Debug:
            /*
            echo '<pre>';
            print_r($stmt->__toString());
            echo '</pre>';
            exit();
            */

            // Run the query.
            $res = $stmt->query();

            // Set the primary ID from the table's last insert ID if this table does not have an identity column    @U01A
            if(!is_array($this->getPrimaryColumnName())){
                $this->_setPrimaryID(self::getAdapter($params['adapter'])->lastInsertId());
                
                // Set the saved data array.
                $this->_savedAttributes = array_merge(array($this->_getPrimaryColumnName() => $this->getPrimaryID()), 
                                                        $this->_getUnsavedAttributes());
            }
            else{
                // Initialize as Arrays for processing                                                              @U01A
                $primaryIDs = array();
                $primaryColumnIDs = array();
                
                // Set each primary ID in the array                                                                 @U01A
                foreach($this->_getPrimaryColumnName() as $primaryColumn){
                    $itemCount = count($primaryIDs);
                    $unsavedAttr = $this->_getUnsavedAttributes();
                    $primaryIDs[$itemCount] = $unsavedAttr[$primaryColumn];                    
                }     
                
                // Set the local primary id                                                                         @U01A
                $this->_setPrimaryID($primaryIDs);
                
                // Set the saved data array.                                                                        @U01A
                $this->_savedAttributes =  $this->_getUnsavedAttributes();
            }           

            // Empty the unsaved attributes and they are no longer needed.
            $this->_emptyUnsavedAttributes();

            // Return the number of effected rows.
            return $res->rowCount();
        }

        /**
         * UPDATEs the unsaved data.
         *
         * Change Log:  
         *  2013-12-22 [DMS@U01] Update to accept multiple columns as primary ids for updating
         *  
         * @access protected
         * @author Jon Matthews
         * @param  array $params
         * @return int
         */
        protected function _update(array $params = array())
        {
            // Adapter.
            if (!isset($params['adapter'])){
                $params['adapter'] = 'default';
            }

            $fieldsToUpdate = array();                

            foreach($this->_getUnsavedAttributes() as $field => $value){

                $fieldsToUpdate[$field] = $value;
                $this->_savedAttributes[$field] = $value;
            }

            // Run the SQL.
            if (count($fieldsToUpdate) >= 1){

                // Checking for multiple columns as primary IDs                                 @U01A
                if(is_array($this->_getPrimaryColumnName())){
                    $primaryColumnNames = $this->_getPrimaryColumnName(); 
                    $primaryIDs = $this->getPrimaryID();
                    // Set the first part of the where phrase                                   @U01A
                    $wherePhrase = $primaryColumnNames[0] . ' = ' . $primaryIDs[0];
                    
                    // Loop to setup the WHERE clause for multiple primary key columns          @U01A
                    for($x=1; $x<count($primaryColumnNames); $x++){
                        $wherePhrase = $wherePhrase . " AND " . $primaryColumnNames[$x] . ' = ' . $primaryIDs[$x];
                    }      
                    
                    // Set statement with multiple columns as primary IDs
                    $stmt = self::getAdapter($params['adapter'])
                            ->update()
                            ->table($this->_getTableName(), $fieldsToUpdate)
                            ->where($wherePhrase)
                            ->limit(1);
                }
                else{
                    $stmt = self::getAdapter($params['adapter'])
                            ->update()
                            ->table($this->_getTableName(), $fieldsToUpdate)
                            ->where($this->_getPrimaryColumnName() . ' = ?', $this->getPrimaryID())
                            ->limit(1);
                }
                
                // Debug:
                /*
                echo '<pre>';
                print_r($stmt->__toString());
                echo '</pre>';
                exit();
                 */

                // Run the query.
                $res = $stmt->query();
            }

            // Empty the unsaved attributes and they are no longer needed.
            $this->_emptyUnsavedAttributes();

            if (isset($res)) {
                // Return the number of effected rows.
                return $res->rowCount();
            } else {
                return 0;
            }
        }

        /**
         * Deletes the instance's databse entry.
         *
         * Change Log:  
         *  2013-12-22 [DMS@U01] Update to accept multiple columns as primary ids for deletion
         *  
         * @static
         * @access public
         * @author Jon Matthews
         * @return TableObject
         */
        public function delete()
        {
            $params = @func_get_arg(0);

            if (!is_array($params)){
                $params = array();
            }

            // Adapter.
            if (!isset($params['adapter'])){
                $params['adapter'] = 'default';
            }

            if ($this->_hasPrimaryID()){

                // Check for multiple columns as primary IDs                                    @U01A
                if(is_array($this->_getPrimaryColumnName())){
                    $primaryColumnNames = $this->_getPrimaryColumnName(); 
                    $primaryIDs = $this->getPrimaryID();
                    // Set first part of the where phrase                                       @U01A
                    $wherePhrase = $primaryColumnNames[0] . ' = ' . $primaryIDs[0];
                    
                    // Loop to setup the WHERE clause for multiple primary key columns          @U01A
                    for($x=1; $x<count($primaryColumnNames); $x++){
                        $wherePhrase = $wherePhrase . " AND " . $primaryColumnNames[$x] . ' = ' . $primaryIDs[$x];
                    }    
                    
                    // Set the statement with the where phrase
                    $stmt = self::getAdapter($params['adapter'])
                            ->delete()
                            ->from($this->_getTableName())
                            ->where($wherePhrase)                            
                            ->limit(1);
                }
                else{
                    $stmt = self::getAdapter($params['adapter'])
                            ->delete()
                            ->from($this->_getTableName())
                            ->where($this->_getPrimaryColumnName() . ' = ?', $this->getPrimaryID())
                            ->limit(1);
                }
                
                // Debug:
                /*
                echo '<pre>';
                print_r($stmt->__toString());
                echo '</pre>';
                exit();
                 */

                $stmt->query();       

            }

            return $this;
        }
    }
}