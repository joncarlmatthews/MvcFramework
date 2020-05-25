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

namespace MvcFramework\Acl\Role
{
    use \MvcFramework\Exception\Exception;
    use \MvcFramework\Registry\Registry;
    
    /**
     * The StdRole class provides methods for creating an instance of a
     * generic Role for use within an Acl
     *
     * @category    MvcFramework
     * @package     Acl
     * @subpackage  Role
     */
    class StdRole implements RoleInterface
    {
        /**
         * The instance's ID
         *
         * @access protected
         * @var mixed
         */
        protected $_roleID = null;

        /**
         * Class constructor. Sets the roleID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $roleID
         * @return mixed
         */
        public function __construct($roleID)
        {
            $this->_roleID = $roleID;
        }

        /**
         * Returns the instance's roleID.
         *
         * @access public
         * @author  Jon Matthews
         * @return mixed
         */
        public function getRoleID()
        {
            return $this->_roleID;
        }
    }
}