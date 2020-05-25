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
     * The UnicodeAlnum validator provides validation for any unicode alpha
     * numeric character with support for punctuation and other common
     * sequences
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class UnicodeAlnum extends ValidatorAbstract
    {
        /**
         * Not almum error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const ERROR_INVALID_CHAR = 'invalidAlnumChar';

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
            $pattern .= '/^[0-9';

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

            // Punctuation? Note that punctuation allows slashes, hyphens
            // parentheses, periods, quotes et al. So, if you set
            // allowPunctuation to TRUE then you *dont* need to set the other
            // individual "allows" to TRUE.
            if ( (isset($this->_options['allowPunctuation'])) 
                    && (true == $this->_options['allowPunctuation']) ){
                $pattern .= '\p{P}';
            }

            // Pipes?
            if ( (isset($this->_options['allowPipe'])) 
                    && (true == $this->_options['allowPipe']) ){
                $pattern .= '|';
            }

            // Forward slash?
            if ( (isset($this->_options['allowForwardslash'])) 
                    && (true == $this->_options['allowForwardslash']) ){
                $pattern .= '\/';
            }

            // Backslash?
            if ( (isset($this->_options['allowBackslash'])) 
                    && (true == $this->_options['allowBackslash']) ){
                $pattern .= '\\';
            }

            // Hyphens?
            if ( (isset($this->_options['allowHyphens'])) 
                    && (true == $this->_options['allowHyphens']) ){
                $pattern .= '\-';
            }

            // Plus?
            if ( (isset($this->_options['allowPlusSign'])) 
                    && (true == $this->_options['allowPlusSign']) ){
                $pattern .= '\+';
            }

            // Parentheses
            if ( (isset($this->_options['allowParentheses'])) 
                    && (true == $this->_options['allowParentheses']) ){
                $pattern .= '\)\(';
            }

            // Period?
            if ( (isset($this->_options['allowPeriod'])) 
                    && (true == $this->_options['allowPeriod']) ){
                $pattern .= '\.';
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