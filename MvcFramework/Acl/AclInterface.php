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
    /**
     * The AclInterface interface provides methods a consistent interface
     * for Acl classes.
     *
     * @category    MvcFramework
     * @package     Acl
     */
    interface AclInterface
    {
        public function addRole(Role\RoleInterface $role);

        public function addResource(Resource\ResourceInterface $resource);

        public function allow($roleID, $resourceID);

        public function isAllowed($roleID, $resourceID);

        public function deny($roleID, $resourceID);

        public function isDenied($roleID, $resourceID);

        public function getRoles();

        public function getResources();
    }
}