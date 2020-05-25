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

namespace MvcFramework\Config\Reader
{
    use \MvcFramework\Exception\Exception;

    /**
     * The ini class provides methods for reading an .ini file.
     *
     * @category   MvcFramework
     * @package    Config
     * @subpackage Reader
     */
    class Ini
    {   
        /**
         * Separator for nesting levels of configuration data identifiers.
         *
         * @var string
         */
        protected $_nestSeparator = '.';

        /**
         * Directory of the file to process.
         *
         * @var string
         */
        protected $_directory;

        /**
         * Set nest separator.
         *
         * @param  string $separator
         * @return self
         */
        public function setNestSeparator($separator)
        {
            $this->_nestSeparator = $separator;
            return $this;
        }

        /**
         * Get nest separator.
         *
         * @return string
         */
        public function getNestSeparator()
        {
            return $this->_nestSeparator;
        }

        /**
         * fromFile(): defined by Reader interface.
         *
         * @see    ReaderInterface::fromFile()
         * @param  string $filename
         * @return array
         * @throws Exception
         */
        public function fromFile($filename)
        {
            if (!is_file($filename) || !is_readable($filename)) {
                throw new Exception(sprintf(
                    "File '%s' doesn't exist or not readable",
                    $filename
                ));
            }

            $this->_directory = dirname($filename);

            set_error_handler(
                function($error, $message = '', $file = '', $line = 0) use ($filename) {
                    throw new Exception(sprintf(
                        'Error reading INI file "%s": %s',
                        $filename, $message
                    ), $error);
                }, E_WARNING
            );
            $ini = parse_ini_file($filename, true);
            restore_error_handler();

            return $this->_process($ini);
        }

        /**
         * fromString(): defined by Reader interface.
         *
         * @param  string $string
         * @return array|bool
         * @throws Exception
         */
        public function fromString($string)
        {
            if (empty($string)) {
                return array();
            }
            $this->_directory = null;

            set_error_handler(
                function($error, $message = '', $file = '', $line = 0) {
                    throw new Exception(sprintf(
                        'Error reading INI string: %s',
                        $message
                    ), $error);
                }, E_WARNING
            );
            $ini = parse_ini_string($string, true);
            restore_error_handler();

            return $this->_process($ini);
        }

        /**
         * Process data from the parsed ini file.
         *
         * @param  array $data
         * @return array
         */
        protected function _process(array $data)
        {
            $config = array();

            // To understand the recursion, see the bottom of this foreach loop.
            foreach ($data as $section => $value) {
                if (is_array($value)) {
                    if (strpos($section, $this->_nestSeparator) !== false) {
                        $section = explode($this->_nestSeparator, $section, 2);
                        $config[$section[0]][$section[1]] = $this->_processSection($value);
                    } else {
                        $config[$section] = $this->_processSection($value);
                    }
                } else {
                    $this->_processKey($section, $value, $config);
                }
            } // To understand the recursion, see the top of this foreach loop.

            return $config;
        }

        /**
         * Process a section.
         *
         * @param  array $section
         * @return array
         */
        protected function _processSection(array $section)
        {
            $config = array();

            foreach ($section as $key => $value) {
                $this->_processKey($key, $value, $config);
            }

            return $config;
        }

        /**
         * Process a key.
         *
         * @param  string $key
         * @param  string $value
         * @param  array  $config
         * @return array
         * @throws Exception
         */
        protected function _processKey($key, $value, array &$config)
        {
            if (strpos($key, $this->_nestSeparator) !== false) {
                $pieces = explode($this->_nestSeparator, $key, 2);

                if (!strlen($pieces[0]) || !strlen($pieces[1])) {
                    throw new Exception(sprintf('Invalid key "%s"', $key));
                } elseif (!isset($config[$pieces[0]])) {
                    if ($pieces[0] === '0' && !empty($config)) {
                        $config = array($pieces[0] => $config);
                    } else {
                        $config[$pieces[0]] = array();
                    }
                } elseif (!is_array($config[$pieces[0]])) {
                    throw new Exception(sprintf(
                        'Cannot create sub-key for "%s", as key already exists', $pieces[0]
                    ));
                }

                $this->_processKey($pieces[1], $value, $config[$pieces[0]]);
            } else {
                if ($key === '@include') {
                    if ($this->_directory === null) {
                        throw new Exception('Cannot process @include statement for a string config');
                    }

                    $reader  = clone $this;
                    $include = $reader->fromFile($this->_directory 
                                                    . DIRECTORY_SEPARATOR 
                                                    . $value);
                    $config  = array_replace_recursive($config, $include);
                } else {
                    $config[$key] = $value;
                }
            }
        }
    }
}