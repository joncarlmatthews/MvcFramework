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

namespace MvcFramework\Log\Writter
{
    use \MvcFramework\Exception\Exception;
    use \MvcFramework\Registry\Registry;

    /**
     * The Db class provides access for writting error messages to a database
     * table.
     *
     * @category    MvcFramework
     * @package     Log
     * @subpackage  Writter
     * @author      Jon Matthews
     */
    class Db extends \MvcFramework\Log\Writter\WritterAbstract
    {
        /**
         * The database handler object.
         *
         * @access private
         * @var \MvcFramework\Db\PdoMySQL
         */
        private $_dbh = null;

        /**
         * The name of the table to write to
         *
         * @access private
         * @var string
         */
        private $_tableName = null;

        /**
         * Class constructor. Sets the log director and the log file name.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $fileName
         * @return \MvcFramework\Log\Writter\Db
         */
        public function __construct(\MvcFramework\Db\PdoMySQL\Driver $dbh,
                                        $tableName = 'app-error-log')
        {
            $this->_dbh         = $dbh;
            $this->_tableName   = $tableName;
        }

        /**
         * Logs the message.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $message
         * @throws \MvcFramework\Exception\Exception
         * @return bool
         */
        public function log($message)
        {
            if (is_null($this->_dbh)) {
                throw new Exception('No database handler defined.');
            }

            if (is_null($this->_tableName)) {
                throw new Exception('No table name defined.');
            }

            $message = '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n";

            $stmt = $this->_dbh->prepare('
                INSERT 
                INTO `' . $this->_tableName . '` 
                    (logMessage, 
                        logCreated)
                VALUES (:logMessage, 
                            NOW())
            ');

            $bind = array('logMessage' => $message);
            $stmt->execute($bind);

            return true;
        }
    }
}