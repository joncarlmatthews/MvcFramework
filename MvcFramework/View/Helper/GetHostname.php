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
     * The GetHostname view helper provides methods for returning the hostname
     * including the protocol and server port.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetHostname extends HelperAbstract
    {
        const PROTOCOL_AUTO     = '';
        const PROTOCOL_HTTP     = 'http';
        const PROTOCOL_HTTPS    = 'https';

        /**
         * Returns the value.
         *
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function getHostname($protocolMode = self::PROTOCOL_AUTO,
                                        $appendBasePath = false,
                                        $doHostname = false)
        {
            $base = $protocolMode;

            if ($protocolMode != self::PROTOCOL_AUTO){
                $base .= ':';
            }

            $base .= '//';
            $base .= $_SERVER['SERVER_NAME'];

            if ($doHostname){
                if ( ($_SERVER['SERVER_PORT'] != 80) || ($_SERVER['SERVER_PORT'] != 443) ){
                    $base .= ':' . $_SERVER['SERVER_PORT'];
                }
            }
            
            if ($appendBasePath){
                $base .= $this->getView()->getBasePath();
            }

            return $base;
        }
    }
}