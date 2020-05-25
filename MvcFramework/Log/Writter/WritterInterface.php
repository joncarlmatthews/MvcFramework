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

namespace MvcFramework\Log\Writter
{
    /**
     * The \MvcFramework\Log\Writter\WritterInterface class provides a consistent 
     * interface for log writter classes.
     * 
     *
     * @category    MvcFramework
     * @package     Log_Writter
     * @subpackage  WritterInterface
     * @author      Jon Matthews
     */
    interface WritterInterface
    { 
        public function log($message);
    }
}