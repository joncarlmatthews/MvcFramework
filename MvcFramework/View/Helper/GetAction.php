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
     * The GetAction view helper provides methods for returning the name of the
     * current action.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetAction extends HelperAbstract
    {
        /**
         * Returns the current action name.
         *
         * @access public
         * @author Jon Matthews
         * @param boolean $lowercase Lowercase the action name?
         * @param boolean $withSuffix Return with suffix?
         * @return string
         */
        public function getAction($lowercase = true, $withSuffix = false)
        {
            $actionName = \MvcFramework\Bootstrap\Core::getBootstrap()->getRequest()->getActionName($withSuffix);

            if ($lowercase){
                $actionName = strtolower($actionName);
            }

            return $actionName;
        }
    }
}