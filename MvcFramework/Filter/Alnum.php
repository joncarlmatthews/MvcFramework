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

namespace MvcFramework\Filter
{
    /**
     * The Alnum class provides methods for filtering a string to alpha numeric
     * characters only.
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  Alnum
     */
    class Alnum extends \MvcFramework\Filter\FilterAbstract
    {
        /**
         * Filter method.
         *
         * @access public
         * @author Jon Matthews
         * @param string $value
         * @return string
         */
        public function filter($value)
        {
            if ( (isset($this->_options['allowWhiteSpace'])) && (true == $this->_options['allowWhiteSpace']) ){
                return preg_replace('/[^0-9a-zA-Z\s]/', null, $value);
            }else{
                return preg_replace('/[^0-9a-zA-Z]/', null, $value);
            }
        }
    }
}