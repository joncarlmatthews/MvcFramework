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

namespace MvcFramework\Db\PdoMySQL
{
    use \MvcFramework\Db\PdoMySQL\Statement\Select;
    use \MvcFramework\Db\PdoMySQL\Statement\Insert;
    use \MvcFramework\Db\PdoMySQL\Statement\Update;
    use \MvcFramework\Db\PdoMySQL\Statement\Delete;

    /**
     * The Driver class provides methods for connecting to and querying a
     * MySQL database via a PDO connection.
     *
     *
     * @category    MvcFramework
     * @package     Db
     * @subpackage  PdoMySQL
     * @see         http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html
     * @see         http://www.kitebird.com/articles/php-pdo.html
     * @see         http://php.net/manual/en/pdo.connections.php
     */
    class Driver extends \PDO implements \MvcFramework\Db\DriverInterface
    {
        /**
         * Convenience method for creating a new Select statement object whilst
         * using the Driver class.
         *
         * @access public
         * @author Jon Matthews
         * @return Select
         */
        public function select()
        {
            return new Select($this);
        }

        /**
         * Convenience method for creating a new Insert statement object whilst
         * using the Driver class.
         *
         * @access public
         * @author Jon Matthews
         * @return Insert
         */
        public function insert()
        {
            return new Insert($this);
        }

        /**
         * Convenience method for creating a new Update statement object whilst
         * using the Driver class.
         *
         * @access public
         * @author Jon Matthews
         * @return Select
         */
        public function update()
        {
            return new Update($this);
        }

        /**
         * Convenience method for creating a new Delete statement object whilst
         * using the Driver class.
         *
         * @access public
         * @author Jon Matthews
         * @return Select
         */
        public function delete()
        {
            return new Delete($this);
        }
    }
}