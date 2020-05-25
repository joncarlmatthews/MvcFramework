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
     * The GetController view helper provides methods for returning the name of the
     * current controller.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetController extends HelperAbstract
    {
        /**
         * Returns the current controller name.
         *
         * @access public
         * @author  Jon Matthews
         * @param boolean $lowercase Lowercase the controller name?
         * @return string
         */
        public function getController($lowercase = true)
        {
            $controllerName = \MvcFramework\Bootstrap\Core::getBootstrap()
                                                            ->getRequest()
                                                            ->getControllerName();

            if ($lowercase){
                $controllerName = strtolower($controllerName);
            }

            return $controllerName;
        }
    }
}