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
     * The UnicodeLetter validator provides validation for any unicode letter
     * with support for punctuation and other common sequences
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class UnicodeLetter extends ValidatorAbstract
    {
        /**
         * Not almum error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const ERROR_INVALID_CHAR = 'invalidChar';

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::ERROR_INVALID_CHAR => 'String contains invalid characters',
        );

        /**
         * Is valid method.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $value
         * @return bool
         */
        public function isValid($value)
        {
            // Short
            // '/^[ \p{L}]+$/u'

            // Full
            // '/^[\/,\- \p{L}]+$/u'

            $pattern  = null;
            $pattern .= '/^[';

            // Whitespace? ( )
            if ( (isset($this->_options['allowWhiteSpace'])) 
                    && (true == $this->_options['allowWhiteSpace']) ){
                $pattern .= ' ';
            }

            // Linebreaks?
            if ( (isset($this->_options['allowLinebreak'])) 
                    && (true == $this->_options['allowLinebreak']) ){
                $pattern .= '\n';
            }

            // Punctuation?
            if ( (isset($this->_options['allowPunctuation'])) 
                    && (true == $this->_options['allowPunctuation']) ){
                $pattern .= '\p{P}';
            }

            // Degree? (°)
            if ( (isset($this->_options['allowDegree'])) 
                    && (true == $this->_options['allowDegree']) ){
                $pattern .= '°';
            }

            $pattern .= '\p{L}';
            $pattern .= ']+$/u';

            $res = preg_match($pattern, $value);

            if(!$res){
                $this->_addMessage(self::ERROR_INVALID_CHAR);
                return false;
            }else{
                return true;
            }
        }
    }
}