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

namespace MvcFramework\Math
{
    /**
     * The Float class provides methods for dealing with floating point numbers.
     *
     * @category    MvcFramework
     * @package     Math
     * @subpackage  Float
     */
    class FloatNum
    {
        /**
         * Always rounds the given number up (to a precision of two decimal places) 
         * if the number given isn't exact.
         *
         * E.g 28.991 becomes 29.00
         * E.g 10.990 remains 10.99
         *
         * @access public
         * @author Jon Matthews
         * @param float|double $float
         * @return float|double
         */
        static public function alwaysRoundUp($float)
        {
            // Cast to float.
            $float = (float)$float;

            // Check the precision of the number
            $parts = explode('.', $float);

            // No decimal places, nothing to do.
            if (!array_key_exists(1, $parts)){
                return $float;
            }

            // How many decimal places have we been give? If <= 2 then we don't
            // have anything more to do.
            if ((strlen($parts[1])) <= 2){
                return $float;
            }

            return round(((ceil($float * 100)) / 100), 2);
        }

        /**
         * Truncates the given number to two decimal places.
         *
         * E.g 0.829 becomes 0.82.
         *
         * @access public
         * @author Jon Matthews
         * @param float|double $float
         * @return float|double
         */
        static public function truncateAtTwoDecimalPlaces($float)
        {
            // Cast to float.
            $float = (float)$float;

            // Check the precision of the number
            $parts = explode('.', $float);

            // No decimal places, nothing to do.
            if (!array_key_exists(1, $parts)){
                return $float;
            }

            // How many decimal places have we been give? If <= 2 then we don't
            // have anything more to do.
            if ((strlen($parts[1])) <= 2){
                return $float;
            }
            
            return round(((floor($float * 100)) / 100), 2);
        }
    }
}