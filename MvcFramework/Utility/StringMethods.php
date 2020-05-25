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

namespace MvcFramework\Utility
{

    /**
     * The ArrayMethods class provides methods for dealing with common array
     * methods.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Utility
     */
    class StringMethods
    {
        const CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        /**
         * Private constructor for singleton
         *
         * @access private
         * @author  Jon Matthews
         * @return StringMethods
         */
        private function __construct(){}
        
        /**
         * Private clone for singleton
         *
         * @access private
         * @author  Jon Matthews
         * @return StringMethods
         */
        private function __clone(){}
        
        /**
         * Returns a random string.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param int $length
         * @return string
         */
        static public function generateRandomString($length = 8)
        {
            $count = strlen(self::CHARS);

            for ($i = 0, $result = null; $i < $length; $i++) {
                $index = rand(0, $count - 1);
                $result .= substr(self::CHARS, $index, 1);
            }

            return $result;
        }

        /**
         * Returns a random patterned string.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @param string $pattern
         * @return string
         */
        static public function generateRandomPatternedString($pattern = 'nvCvxVcx')
        {
            $c = "aeiou";
            $v = "bcdfghjklmnpqrstvwxyz";
            $n = "123456789";
            $x = '!@%&*()+-?=';
            $pwd = "";
            for ( $i=0; $i<strlen($pattern); $i++ ){
                $char = substr($pattern, $i, 1);
                switch ( $char ) {
                    case "c":
                        $pwd .= substr($c, rand(0, strlen($c)-1), 1);
                    break;
                    case "C":
                        $tmp = (substr($c, rand(0, strlen($c)-1), 1));
                        $pwd .= strtoupper( $tmp );
                    break;
                    case "v":
                        $pwd .= substr($v, rand(0, strlen($v)-1), 1);
                    break;
                    case "V":
                        $tmp = substr($v, rand(0, strlen($v)-1), 1);
                        $pwd .= strtoupper( $tmp );
                    break;
                    case "n":
                        $pwd .= substr($n, rand(0, strlen($n)-1), 1);
                    break;
                    case "x":
                        $pwd .= substr($x, rand(0, strlen($x)-1), 1);
                    break;
                }
            }
            return (strtoupper($pwd));
        }

        /**
         * Converts a query string to an array.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @return string
         */
        static public function queryStringToArray($string, $delimeter = "&")
        {
            // Explode query by delimiter
            $pairs = explode($delimeter, $string);
            $queryArray = array();

            // Explode pairs by "="
            foreach ($pairs as $pair)
            {
                $keyValue = explode('=', $pair);

                // Use first value as key
                $key = array_shift($keyValue);

                // Implode others as value for $key
                $queryArray[$key] = implode('=', $keyValue);
            }

            return $queryArray;
        }

        /**
         * Mask an email address with a character.
         *
         * @static
         * @access public
         * @author Jon Matthews
         * @return string
         */
        static public function maskEmailAddress($maskEmailAddress, $maskChar = '*')
        {
            return preg_replace('/(?<=.).(?=.*?@)|(?<=@.).*(?=\.com)/u', 
                                                                $maskChar, 
                                                                $maskEmailAddress);
        }
    }
}