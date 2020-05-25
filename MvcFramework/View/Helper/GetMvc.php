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
     * The GetMvc view helper provides methods for returning a concatenation
     * of the module, controller and view names.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetMvc extends HelperAbstract
    {
        /**
         * The default part separator.
         *
         * @access private
         * @var string
         */
        private $_separator = '-';

        /**
         * The concatenation. Static so the helper doesnt have to keep calculating
         * it.
         *
         * @static
         * @access private
         * @var string
         */
        static private $_mvc = null;

        /**
         * Returns the concatentation.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $separator part separator
         * @return string
         */
        public function getMvc($separator = null)
        {
            if (!is_null(self::$_mvc)){

                $mvc = self::$_mvc;

                if ( (!is_null($separator)) && ($separator != $this->_separator)){
                    $mvc = str_replace($this->_separator, $separator, $mvc);
                }

                return $mvc;

            }else{

                $mvc  = null; 
                $mvc .= $this->getView()->getModule();
                $mvc .= $this->_separator;
                $mvc .= str_replace('\\', '_', $this->getView()->getController());
                $mvc .= $this->_separator;
                $mvc .= $this->getView()->getAction();

                self::$_mvc = $mvc;

                if ( (!is_null($separator)) && ($separator != $this->_separator)){
                    $mvc = str_replace($this->_separator, $separator, $mvc);
                }

                return $mvc;
            }
        }
    }
}