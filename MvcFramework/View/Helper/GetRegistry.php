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

namespace MvcFramework\View\Helper
{
    
    use \MvcFramework\Registry\Registry;

    /**
     * The GetRegistry view helper provides methods for returning the Registry 
     * instance.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetRegistry extends HelperAbstract
    {
        /**
         * Return the Registry object.
         *
         * @access public
         * @author Jon Matthews
         * @return Registry
         */
        public function getRegistry()
        {
            return Registry::getInstance();
        }
    }
}