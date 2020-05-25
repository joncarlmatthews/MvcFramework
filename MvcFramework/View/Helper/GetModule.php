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
    /**
     * The GetModule view helper provides methods for returning the name of the
     * current module.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetModule extends HelperAbstract
    {
        /**
         * Returns the current module name.
         *
         * @access public
         * @author Jon Matthews
         * @param boolean $lowercase Lowercase the module name?
         * @return string
         */
        public function getModule($lowercase = true)
        {
            $moduleName = \MvcFramework\Bootstrap\Core::getBootstrap()
                                                            ->getRequest()
                                                            ->getModuleName();

            if ($lowercase){
                $moduleName = strtolower($moduleName);
            }

            return $moduleName;
        }
    }
}