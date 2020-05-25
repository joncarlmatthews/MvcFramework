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

namespace MvcFramework\View\Helper
{
    use \MvcFramework\Exception\Exception;

    /**
     * The Translate view helper provides methods for returning a translated
     * message.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class Translate extends Translator\HelperAbstract
    {
        /**
         * Translate a message.
         *
         * @param  string $messageID        The key
         * @param  string $termDomain       The domain
         * @param  string $values           Wildcard/value replacements
         * @param  string $fallbackMessage  If no key is found...
         * @return string
         */
        public function translate($messageID,
                                    $termDomain = null,
                                    array $values = array(),
                                    $fallbackMessage = null)
        {
            $translator = $this->getTranslator();

            if (null === $translator) {
                throw new Exception('Translator has not been set');
            }

            return $translator->translate($messageID,
                                            $termDomain,
                                            $values,
                                            $fallbackMessage);
        }
    }
}