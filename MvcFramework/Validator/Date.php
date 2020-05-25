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
    use \MvcFramework\Exception\Exception;
    
    /**
     * Is numeric validator
     *
     * @author      Greg Burke <greg.burke@swc.com>
     * @category    MvcFramework
     * @package     Validator
     */
    class Date extends ValidatorAbstract
    {
        /**
         * Before minimum data error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const IS_BEFORE_MIN = 'isBeforeMin';
        
        /**
         * Before minimum data error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const IS_AFTER_MAX = 'isAfterMax';

        /**
         * Unknown error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const UNKNOWN = 'unknown';
        
        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::IS_BEFORE_MIN => 'Date is before %%min%%',
            self::IS_AFTER_MAX => 'Date is after %%max%%',
            self::UNKNOWN       => 'Date is invalid',
        );
        
        /**
         * Error message template variables.
         *
         * @access protected
         * @var array
         */
        protected $_messageVariables = array(
            'min' => array('options' => 'min'),
            'max' => array('options' => 'max'),
        );

        /**
         * Is valid method.
         *
         * @access public
         * @author  Greg Burke <greg.burke@swc.com>
         * @param string $value
         * @return bool
         */
        public function isValid($value)
        {
            $max = false;
            if (isset($this->_options['max'])){
                $max = $this->_options['max'];
            }

            $min = false;
            if (isset($this->_options['min'])){
                $min = $this->_options['min'];
            }

            if (false === $max && false === $min){
                throw new Exception('No max or min date to
                        check values against');
            }
            
            // Are both values set?
            if (!empty($min) && !empty($max)){

                $date    = date('Ymd', strtotime($value));
                $minDate = date('Ymd', strtotime($min));
                $maxDate = date('Ymd', strtotime($max));
                
                if ($min > $max){
                    throw new Exception('Min value greater than max.');
                }

                if ($date >= $minDate && $date <= $maxDate) {
                    return true;   
                } else {
                    if ($date < $minDate) {
                        $this->_addMessage(self::IS_BEFORE_MIN); 
                        return false;
                    } elseif($date > $maxDate) {
                        $this->_addMessage(self::IS_AFTER_MAX);
                        return false;
                    }
                }
            }

            // Is min only set?
            if (!empty($min)){
                $date    = date('Ymd', strtotime($value));
                $minDate = date('Ymd', strtotime($min));
                
                if ($date < $minDate){
                    $this->_addMessage(self::IS_BEFORE_MIN);
                    return false;
                }else{
                    return true;
                }
            }

            // Is max only set?
            if (!empty($max)){
                $date    = date('Ymd', strtotime($value));
                $maxDate = date('Ymd', strtotime($max));
                
                if ($date > $maxDate){
                    $this->_addMessage(self::IS_AFTER_MAX);
                    return false;
                }else{
                    return true;
                }
            }


            // Code flow shouldnt get this far, but just in case:
            $this->_addMessage(self::UNKNOWN);
            return false;
        }
    }
}