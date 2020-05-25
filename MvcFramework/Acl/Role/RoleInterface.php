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
    /**
     * The RoleInterface interface provides methods a consistent interface
     * for Acl Role classes.
     *
     * @category    MvcFramework
     * @package     Acl
     * @subpackage  Role
     */
    interface RoleInterface
    {
        /**
         * Class constructor. Sets the roleID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $roleID
         * @return mixed
         */
        public function __construct($roleID);

        /**
         * Returns the instance's roleID.
         *
         * @access public
         * @author  Jon Matthews
         * @return mixed
         */
        public function getRoleID();
    }
}