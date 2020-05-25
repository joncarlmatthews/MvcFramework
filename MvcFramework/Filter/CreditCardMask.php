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
     * The CreditCardMask class provides methods for masking a credit card
     * number
     *
     * @category    MvcFramework
     * @package     Filter
     * @see http://bit.ly/xZzuXA
     */
    class CreditCardMask extends \MvcFramework\Filter\FilterAbstract
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
            // Get the cc Length
            $valueLength = strlen($value);

            // Replace all characters of credit card except the last four 
            // and dashes
            for ($i = 0; $i < $valueLength - 4; $i++){
                if ($value[$i] == '-'){
                    continue;
                }

                $value[$i] = '*';
            }

            // Return the masked Credit Card
            return $value;
        }
    }
}