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

namespace MvcFramework\Acl
{
    use \MvcFramework\Exception\Exception;
    use \MvcFramework\Registry\Registry;
    
    /**
     * The Acl class provides methods for creating a generic ACL which consists
     * of Roles and Resources.
     *
     * Example usage:
     *
     * $acl = new Acl;
     *
     * $role = new StdRole('admin');
     * $acl->addRole($role);
     *
     * $resource = new StdResource('edit_users');
     * $acl->addResource($resource);
     *
     * $acl->allow('admin', 'edit_users');
     *
     * if ($acl->isAllowed('admin', 'edit_users')){
     *     echo 'Access granted!';
     * }else{
     *     echo 'Access denied!';
     * }
     *
     * @category    MvcFramework
     * @package     Acl
     */
    class Acl implements AclInterface
    {
        /**
         * Roles.
         *
         * @access protected
         * @var array
         */
        protected $_roles = array();

        /**
         * Resources.
         *
         * @access protected
         * @var array
         */
        protected $_resources = array();

        /**
         * Allow rules.
         *
         * @access protected
         * @var array
         */
        protected $_rulesAllow = array();

        /**
         * Deny rules.
         *
         * @access protected
         * @var array
         */
        protected $_rulesDeny = array();

        /**
         * Adds a Role to the Acl.
         *
         * @access public
         * @author  Jon Matthews
         * @param RoleInterface $role
         * @return Acl
         */
        public function addRole(Role\RoleInterface $role)
        {
            $roleID = $role->getRoleID();

            $this->_roles[$roleID] = $role;

            return $this;
        }

        /**
         * Adds a Resource to the Acl.
         *
         * @access public
         * @author  Jon Matthews
         * @param ResourceInterface $resource
         * @return Acl
         */
        public function addResource(Resource\ResourceInterface $resource)
        {
            $resourceID = $resource->getResourceID();

            $this->_resources[$resourceID] = $resource;

            return $this;
        }

        /**
         * Sets the allow rule for $roleID to access $resourceID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $roleID
         * @param mixed $resourceID
         * @return Acl
         */
        public function allow($roleID, $resourceID)
        {
            $this->_rulesAllow[$roleID][] = $resourceID;

            return $this;
        }

        /**
         * Checks whether $roleID has been granted access to $resourceID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $roleID
         * @param mixed $resourceID
         * @return bool
         */
        public function isAllowed($roleID, $resourceID)
        {
            // Deny takes precedence over allow.
            if ($this->isDenied($roleID, $resourceID)){
                return false;
            }
            
            if (array_key_exists($roleID, $this->_rulesAllow)){
                if (in_array($resourceID, $this->_rulesAllow[$roleID])){
                    return true;
                }
            }
            return false;
        }

        /**
         * Sets the deny rule for $roleID to access $resourceID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $roleID
         * @param mixed $resourceID
         * @return Acl
         */
        public function deny($roleID, $resourceID)
        {
            $this->_rulesDeny[$roleID][] = $resourceID;

            return $this;
        }

        /**
         * Checks whether $roleID has been denied access to $resourceID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $roleID
         * @param mixed $resourceID
         * @return bool
         */
        public function isDenied($roleID, $resourceID)
        {
            if (array_key_exists($roleID, $this->_rulesDeny)){
                if (in_array($resourceID, $this->_rulesDeny[$roleID])){
                    return true;
                }
            }
            return false;
        }

        /**
         * Returns a list of all declared Roles
         *
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        public function getRoles()
        {
            return $this->_roles;
        }

        /**
         * Returns a list of all declared Resources
         *
         * @access public
         * @author  Jon Matthews
         * @return array
         */
        public function getResources()
        {
            return $this->_resources;
        }
    }
}