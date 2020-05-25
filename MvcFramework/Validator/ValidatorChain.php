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
     * The ValidatorChain class provides an interface for stringing together
     * multiple validator objects into a single isValid() call.
     *
     * @author      Jon Matthews
     * @category    MvcFramework
     * @package     Validator
     */
    class ValidatorChain extends ValidatorAbstract
    {
        /**
         * Array of validator objects.
         *
         * @access protected
         * @var array
         */
        protected $_validators = array();

        /**
         * Adds a validator to the chain.
         *
         * @access  public
         * @author  Jon Matthews
         * @param   ValidatorInterface  $validator
         * @param   bool                $breakChainOnFailure    Whether or not
         *                                                      to break the
         *                                                      validation chain
         *                                                      if this
         *                                                      validators
         *                                                      isValid()
         *                                                      method returns
         *                                                      FALSE.
         * @return  ValidatorChain
         */
        public function addValidator(\MvcFramework\Validator\ValidatorInterface $validator, 
                                        $breakChainOnFailure = false)
        {
            $this->_validators[] = array(
                'instance'            => $validator,
                'breakChainOnFailure' => (boolean)$breakChainOnFailure
            );
            return $this;
        }

        /**
         * Retuns the array of registered validators
         *
         * @access public
         * @author Jon Matthews
         * @return array
         */
        public function getValidators()
        {
            return $this->_validators;
        }

        /**
         * Is valid method. Calls each of the @link $_validators isValid() 
         * method in turn.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $value
         * @return bool
         */
        public function isValid($value)
        {
            $result = true;

            if (empty($this->_validators)){
                throw new Exception('No validators set. Set
                        with \MvcFramework\Validator::addValidator()');
            }

            foreach ($this->_validators as $element) {

                $validator = $element['instance'];

                if ($validator->isValid($value)) {
                    continue;
                }

                $result             = false;
                $messages           = $validator->getMessages();
                $this->_messages    = array_replace_recursive($this->_messages, 
                                                                    $messages);

                if ($element['breakChainOnFailure']) {
                    break;
                }
            }
            return $result;
        }
    }
}