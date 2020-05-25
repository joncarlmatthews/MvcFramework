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
     * The CreditCardFormat class provides methods for formatting a credit card
     * number
     *
     * @category    MvcFramework
     * @package     Filter
     * @see http://bit.ly/xZzuXA
     */
    class CreditCardFormat extends \MvcFramework\Filter\FilterAbstract
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
            $cc = $value;

            // Clean out extra data that might be in the cc.
            $cc = preg_replace('/[^0-9]/', null, $cc);

            // Get the CC Length.
            $ccLength = strlen($cc);

            // Initialize the new credit card to contian the last four digits.
            $newCreditCard = substr($cc,-4);

            // Walk backwards through the credit card number and add a dash 
            // after every fourth digit.
            for($i=$ccLength-5;$i>=0;$i--){

                // If on the fourth character add a dash
                if((($i+1)-$ccLength)%4 == 0){
                    $newCreditCard = '-'.$newCreditCard;
                }

                // Add the current character to the new credit card.
                $newCreditCard = $cc[$i].$newCreditCard;
            }

            // Return the formatted credit card number.
            return $newCreditCard;
        }
    }
}