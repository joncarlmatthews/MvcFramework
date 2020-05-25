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
    // Independent Luhn checker.
    use \MvcFramework\Validator\Luhn;

    /**
     * The CreditCard class provides methods for checking the validity of a 
     * credit card (including checks against the credit card type).
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class CreditCard extends ValidatorAbstract
    {
        /**
         * Invalid error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const INVALID_NUMBER                = 'invalidIdentificationNumber';
        const INVALID_CARD_NUMBER_COMBO     = 'invalidCardTypeCardNumberCombo';
        const NO_CARD_TYPE                  = 'noCardTypeSupplied';
        const INVALID_CARD_TYPE             = 'invalidCardType';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::INVALID_NUMBER                => 'Card number is invalid',
            self::INVALID_CARD_NUMBER_COMBO     => 'The format of your card number does not match the selected card type',
            self::NO_CARD_TYPE                  => 'No card type provided',
            self::INVALID_CARD_TYPE             => 'The card type provided is not supported',
        );

        private $_cards = array (

                    'amex' =>  
                        array ('name' => 'American Express',
                              'length' => '15', 
                              'prefixes' => '34,37',
                              'checkdigit' => true
                             ),

                    'diners club carte blanche' 
                        => array ('name' => 'Diners Club Carte Blanche', 
                              'length' => '14', 
                              'prefixes' => '300,301,302,303,304,305',
                              'checkdigit' => true
                             ),

                    'diners club' 
                        => array ('name' => 'Diners Club', 
                              'length' => '14,16',
                              'prefixes' => '36,38,54,55',
                              'checkdigit' => true
                             ),

                    'discover' 
                        => array ('name' => 'Discover', 
                              'length' => '16', 
                              'prefixes' => '6011,622,64,65',
                              'checkdigit' => true
                             ),

                    'diners club enroute' 
                        => array ('name' => 'Diners Club Enroute', 
                              'length' => '15', 
                              'prefixes' => '2014,2149',
                              'checkdigit' => true
                             ),

                    'jcb' 
                        => array ('name' => 'JCB', 
                              'length' => '16', 
                              'prefixes' => '35',
                              'checkdigit' => true
                             ),

                    'maestro' 
                        => array ('name' => 'Maestro', 
                              'length' => '12,13,14,15,16,18,19', 
                              'prefixes' => '5018,5020,5038,6304,6759,6761,6762,6763',
                              'checkdigit' => true
                             ),

                    'mastercard' 
                        => array ('name' => 'Mastercard', 
                              'length' => '16', 
                              'prefixes' => '51,52,53,54,55',
                              'checkdigit' => true
                             ),

                    'solo' 
                        => array ('name' => 'Solo', 
                              'length' => '16,18,19', 
                              'prefixes' => '6334,6767',
                              'checkdigit' => true
                             ),

                    'switch' 
                        => array ('name' => 'Switch', 
                              'length' => '16,18,19', 
                              'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
                              'checkdigit' => true
                             ),

                    'visa' 
                        => array ('name' => 'VISA', 
                              'length' => '16', 
                              'prefixes' => '4',
                              'checkdigit' => true
                             ),

                    'visa electron' 
                        => array ('name' => 'VISA Electron', 
                              'length' => '16', 
                              'prefixes' => '417500,4917,4913,4508,4844',
                              'checkdigit' => true
                             ),

                    'lasercard' 
                        => array ('name' => 'LaserCard', 
                              'length' => '16,17,18,19', 
                              'prefixes' => '6304,6706,6771,6709',
                              'checkdigit' => true
                             ),
                );

        /**
         * Is valid method.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   string $number
         * @uses    \MvcFramework\Validator\Luhn
         * @return  bool
         */
        public function isValid($number)
        {
            // Validate the number against the Luhn check before anything else.
            $luhnValidator = new Luhn;

            if (!$luhnValidator->isValid($number)){
                $this->_addMessage(self::INVALID_NUMBER);
                return false;
            }

            // Was a card type supplied?
            if ( (isset($this->_options['cardType'])) 
                    && (strlen($this->_options['cardType']) > 0) ){

                // Card type supplied.
                $cardType = $this->_options['cardType'];

            }else{

                // Card type not supplied.
                $cardType = null;

                if ( (isset($this->_options['errorOnNoCardTypeSupplied'])) 
                        && (true == $this->_options['errorOnNoCardTypeSupplied']) ){
                    $this->_addMessage(self::NO_CARD_TYPE);
                    return false;
                }

            }

            $hasCardType = false;
            if (strlen($cardType) >= 1){
                if (isset($this->_cards[$cardType])){
                    $hasCardType = true;
                }
            }

            // Was the card type found?
            if ( (isset($this->_options['errorOnInvalidCardTypeSupplied'])) 
                    && (true == $this->_options['errorOnInvalidCardTypeSupplied'])
                    && (!$hasCardType) ){
                $this->_addMessage(self::INVALID_CARD_TYPE);
                return false;
            }

            // If a card type has been supplied then process additional 
            // validation.
            if ($hasCardType){

                // Load an array with the valid prefixes for this card.
                $prefix = explode(',', $this->_cards[$cardType]['prefixes']);
                  
                // Now see if any of them match what we have in the card number.
                $PrefixValid = false; 
                for ($i=0; $i<sizeof($prefix); $i++) {
                    $exp = '/^' . $prefix[$i] . '/';
                    if (preg_match($exp,$number)) {
                        $PrefixValid = true;
                        break;
                    }
                }
                  
                // If it isn't a valid prefix there's no point at looking at the length.
                if (!$PrefixValid) {
                    $this->_addMessage(self::INVALID_CARD_NUMBER_COMBO);
                    return false; 
                }

                // See if the length is valid for this card.
                $LengthValid = false;
                $lengths = explode(',', $this->_cards[$cardType]['length']);
                for ($j=0; $j<sizeof($lengths); $j++) {
                    if (strlen($number) == $lengths[$j]) {
                        $LengthValid = true;
                        break;
                    }
                }

                // See if all is OK by seeing if the length was valid. 
                if (!$LengthValid) {
                    $this->_addMessage(self::INVALID_CARD_NUMBER_COMBO);
                    return false;
                };

            }

            // The credit card is in the required format.
            return true;
        }
    }
}