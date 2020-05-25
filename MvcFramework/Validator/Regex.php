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
     * Regular expression validator
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class Regex extends ValidatorAbstract
    {
        /**
         * Did not match error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const NOT_MATCH  = 'notMatch';

        /**
         * Regex error error message key.
         *
         * @static
         * @access public
         * @param string
         */
        const ERROR      = 'error';

        /**
         * Array to hold regex matches.
         *
         * @access private
         * @param NULL|array
         */
        private $_matches = null;

        /**
         * Error message templates.
         *
         * @access protected
         * @var array
         */
        protected $_messageTemplates = array(
            self::NOT_MATCH => 'The input does not match against pattern \'%%pattern%%\'',
            self::ERROR  => 'There was an internal error while using the pattern \'%%pattern%%\'',
        );

        /**
         * Message template variables.
         *
         * @access protected
         * @var array
         */
        protected $_messageVariables = array(
            'pattern' => array('options' => 'pattern')
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
            if (!isset($this->_options['pattern'])){
                throw new Exception('No regular expression to check 
                                                                value against.');
            }

            $pattern = $this->_options['pattern'];

            $res = preg_match($pattern, $value, $matches);

            $this->_matches = $matches;

            if (false === $res){
                $this->_addMessage(self::ERROR);
                return false;
            }

            if (!$res){
                $this->_addMessage(self::NOT_MATCH);
                return false;
            }else{
                return true;
            }
        }

        /**
         * Getter for @link $_matches
         *
         * @access public
         * @author  Jon Matthews
         * @return string
         */
        public function getMatches()
        {
            return $this->_matches;
        }
    }
}