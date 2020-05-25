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

namespace MvcFramework\View\Helper\Translator
{
    use \MvcFramework\Exception\Exception;

    /**
     * The abstract HelperAbstract class provides a base class for Translation
     * helpers to extend.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    abstract class HelperAbstract extends \MvcFramework\View\Helper\HelperAbstract
    {
        /**
         * The TranslatorInterface object
         *
         * @access protected
         * @var TranslatorInterface
         */
        protected $_translator = null;

        /**
         * Sets the Translator onject
         *
         * @access public
         * @param TranslatorInterface $translator
         * @return void
         */
        public function setTranslator(\MvcFramework\I18n\Translator\TranslatorInterface $translator)
        {
            $this->_translator = $translator;
        }

        /**
         * Returns the translator object
         *
         * @access public
         * @return TranslatorInterface
         */
        public function getTranslator()
        {
            return $this->_translator;
        }
    }
}