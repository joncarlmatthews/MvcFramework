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
     * The abstract ValidatorAbstract class provides a base class for validation
     * classes to extend.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    abstract class ValidatorAbstract implements ValidatorInterface
    {
        /**
         * An array of options for the validator class.
         *
         * @access protected
         * @param array
         */
        protected $_options = array();

        /**
         * An array of validator messages.
         *
         * @access protected
         * @param array
         */
        protected $_messages = array();

        /**
         * An array of overwritten validator messages.
         *
         * @access protected
         * @param array
         */
        protected $_messageOverwrites = array();

        /**
         * The request object.
         *
         * @access protected
         * @param Request
         */
        protected $_request = null;

        /**
         * Class construction. Sets @link $_options.
         *
         * @access public
         * @author  Jon Matthews
         * @param array $options
         * @return ValidatorAbstract
         */
        public function __construct(array $options = array())
        {
            $this->_options = $options;
            $this->_request = \MvcFramework\Bootstrap\Core::getBootstrap()->getRequest();
        }

        /**
         * Getter for @link $_request
         *
         * @access public
         * @author Jon Matthews
         * @return Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        /**
         * Adds a message to the @link $_message array
         *
         * @access public
         * @author  Jon Matthews
         * @param string $code
         * @return void
         */
        protected function _addMessage($code, array $codeWildcards = [])
        {
            if (!property_exists($this, '_messageTemplates')){
                throw new Exception('$_messageTemplates property 
                    not found in ' . get_class($this));
            }

            if (!isset($this->_messageTemplates[$code])){
                throw new Exception('$_messageTemplates key
                    "' . $code . '" not found in ' . get_class($this));
            }

            $msgData = array();

            if (is_array($codeWildcards)){

                // ** Support for new method of translating **
                $msgData['template']    = strtolower($this->_messageTemplates[$code]);
                $msgData['message']     = $this->_messageTemplates[$code];
                $msgData['wildcards']   = $codeWildcards;


            }else{

                // ** Backwards compatability **

                // Has this message been overwritten?
                if (array_key_exists($code, $this->_messageOverwrites)){

                    // ...yes, use overwritten message text.

                    $msgData['template']    = strtolower($this->_messageOverwrites[$code]);
                    $msgData['message']     = $this->_messageOverwrites[$code];
                    $msgData['wildcards']   = array();

                }else{

                    $messageText = $this->_messageTemplates[$code];

                    $msgData['template'] = strtolower($messageText);

                    $wildcards = array();

                    if ( (property_exists($this, '_messageVariables'))
                            && (is_array(@$this->_messageVariables)) ){

                        foreach($this->_messageVariables as $wildcard => $messageVariable){

                            if (is_array($messageVariable)){

                                foreach($messageVariable as $optionKey){

                                    if (isset($this->_options[$optionKey])){

                                        if (preg_match('#(%%' . $wildcard . '%%)#', $messageText)){

                                            $wildcards['%%' . $wildcard . '%%'] = $this->_options[$optionKey];

                                            $messageText = str_replace('%%' . $wildcard . '%%', 
                                                                        $this->_options[$optionKey],
                                                                        $messageText);

                                        }
                                    }
                                }
                            }
                        }
                    }

                    $msgData['message']     = $messageText;
                    $msgData['wildcards']   = $wildcards;

                }

            }

            $this->_messages[$code] = $msgData;
        }

        /**
         * Getter for @link $_messages
         *
         * @access public
         * @author Jon Matthews
         * @return array
         */
        public function getMessages()
        {
            return $this->_messages;
        }

        /**
         * Overwrite a message's content thus bypassing the wildcard replacement
         * code.
         *
         * @access public
         * @author Jon Matthews
         * @param string $code The index
         * @param string $messageText
         * @return void
         */
        public function overwriteMessageBody($code, $messageText)
        {
            $this->_messageOverwrites[$code] = $messageText;
        }

        /**
         * Overwrite a message's template.
         * code.
         *
         * @todo
         *
         * @access public
         * @author Jon Matthews
         * @param string $code The index
         * @param string $messageText
         * @return void
         */
        public function overwriteMessageTemplate($code, $messageText)
        {
            // @todo.
        }

        /**
         * Overwrite a message already in the stack.
         *
         * @depreciated
         *
         * @see ValidatorAbstract::overwriteMessageBody
         * @see ValidatorAbstract::overwriteMessageTemplate
         *
         * @access public
         * @author Jon Matthews
         * @param string $code The index
         * @param string $messageText
         * @return void
         */
        public function overwriteMessage($code, $messageText)
        {
            $this->_messages[$code]['message'] = $messageText;
            $this->_messages[$code]['template'] = $messageText;
            $this->_messages[$code]['wildcard'] = '';
        }

        /**
         * Getter for @link $_options
         *
         * @access public
         * @author Jon Matthews
         * @return array
         */
        public function getOptions()
        {
            return $this->_options;
        }

        /**
         * Returns a single option from the @link $_options array
         *
         * @access public
         * @author Jon Matthews
         * @param string $code The index
         * @return string
         */
        public function getOption($key)
        {
            if (isset($this->_options[$key])){
                return $this->_options[$key];
            }
            
            throw new Exception('Option key "' . $key . '" does not exist');
        }
    }
}