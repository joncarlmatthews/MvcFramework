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

namespace MvcFramework\Acl\Resource
{
    use \MvcFramework\Exception\Exception;
    use \MvcFramework\Registry\Registry;
    
    /**
     * The StdResource class provides methods for creating an instance of a
     * generic Resource for use within an Acl
     *
     * @category    MvcFramework
     * @package     Acl
     * @subpackage  Resource
     */
    class StdResource implements ResourceInterface
    {
        /**
         * The instance's ID
         *
         * @access protected
         * @var mixed
         */
        protected $_resourceID = null;

        /**
         * Class Constructor. Sets @link $_resourceID.
         *
         * @access public
         * @author  Jon Matthews
         * @param mixed $resourceID
         * @return StdResource
         */
        public function __construct($resourceID)
        {
            $this->_resourceID = $resourceID;
        }

        /**
         * Returns the instance's resource ID.
         *
         * @access public
         * @author  Jon Matthews
         * @return mixed
         */
        public function getResourceID()
        {
            return $this->_resourceID;
        }
    }
}