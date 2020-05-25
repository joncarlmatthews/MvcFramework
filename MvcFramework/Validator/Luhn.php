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

namespace MvcFramework\Validator
{
    /**
     * Checks a string for luhn validity.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class Luhn extends ValidatorAbstract
    {
        /**
         * Invalid error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const INVALID = 'invalidIdentificationNumber';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::INVALID => 'Card number is invalid',
        );

        /**
         * Is valid method.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string $number
         * @see     http://stackoverflow.com/a/174750
         * @return  bool
         */
        public function isValid($number)
        {
            // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
            $number = preg_replace('/\D/', null, $number);

            // Debug:
            /*
            echo 'number is: "' . $number . '"';
            echo '<br>';
            */

            if (0 == strlen($number)){
                $this->_addMessage(self::INVALID);
                return false;
            };

            // Set the string length and parity
            $number_length = strlen($number);
            $parity = $number_length % 2;

            // Loop through each digit and do the maths
            $total = 0;

            for ($i = 0; $i < $number_length; $i++) {

                $digit = $number[$i];

                // Multiply alternate digits by two
                if ($i % 2 == $parity) {

                    $digit *= 2;

                    // If the sum is two digits, add them together (in effect)
                    if ($digit > 9) {
                        $digit -= 9;
                    }
                }

                // Total up the digits
                $total += $digit;
            }

            // If the total mod 10 equals 0, the number is valid
            if ($total % 10 == 0){
                return true;
            }else{
                $this->_addMessage(self::INVALID);
                return false;
            }
        }
    }
}