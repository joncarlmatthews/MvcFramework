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

namespace MvcFramework\I18n\Translator
{
    /**
     * The TranslatorInterface interface provides methods a consistent interface
     * for Translator classes.
     *
     * @category    MvcFramework
     * @package     I18n
     * @subpackage  Translator
     */
    interface TranslatorInterface
    {
        /**
         * Set the locale.
         *
         * @param  object $locale
         * @return Translator
         */
        public function setLocale($locale);
        
        /**
         * Get the locale.
         *
         * @return object
         */
        public function getLocale();

        /**
         * Get the locale ID (convenience method).
         *
         * @return string
         */
        public function getLocaleID();

        /**
         * Translate a message.
         *
         * @param  string $message
         * @param  string $textDomain
         * @return string
         */
        public function translate($message, $textDomain = null);
    }
}