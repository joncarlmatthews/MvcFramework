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

namespace MvcFramework\Log\Writter
{
    use \MvcFramework\Exception\Exception;

    /**
     * The File class provides access for writting error messages to flat files.
     *
     * @category    MvcFramework
     * @package     Log
     * @subpackage  Writter
     * @author      Jon Matthews
     */
    class File extends \MvcFramework\Log\Writter\WritterAbstract
    {
        /**
         * The directory in which the log file resides.
         *
         * @access private
         * @var string
         */
        private $_logDir;

        /**
         * The name of the file in which to write to.
         *
         * @access private
         * @var string
         */
        private $_fileName;

        /**
         * Class constructor. Sets the log director and the log file name.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $fileName
         * @return \MvcFramework\Log\Writter\File
         */
        public function __construct($fileName = 'app.error.log')
        {
            $this->_logDir = dirname(APP_PATH) 
                                . DIRECTORY_SEPARATOR 
                                . 'var'
                                . DIRECTORY_SEPARATOR 
                                . 'log'
                                . DIRECTORY_SEPARATOR;

            // Replace backslashes with forward slashes.
            $fileName = str_replace('\\', '/', $fileName);

            // Remove any directory changing values.
            $fileName = str_replace(array('../', './', '/'), null, $fileName);

            // Set the filename.
            $this->_fileName = $fileName;
        }

        /**
         * Logs the message.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $message
         * @throws \MvcFramework\Exception\Exception
         * @return bool
         */
        public function log($message)
        {
            if (!is_dir($this->_logDir)) {
                throw new Exception('Log directory (' 
                                        . $this->_logDir 
                                        . ') doesnt exist.');
            }

            $message = '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n";
            $logFile = $this->_logDir . $this->_fileName;

            if (0 == strlen($logFile)){
                throw new Exception('No log file to write to.');
            }

            if (!is_file($logFile)) {

                $create = @touch($logFile);

                if (!$create) {
                     throw new Exception('Cannot create log file "' 
                                        . $logFile 
                                        . '"');
                }
            }

            if (is_writable($logFile)) {

                if (!$handle = fopen($logFile, 'a')) {
                     throw new Exception('Cannot open log file "' 
                                        . $logFile 
                                        . '" for writting');
                }

                if (fwrite($handle, $message) === FALSE) {
                    throw new Exception('Cannot write to file "' 
                                        . $logFile 
                                        . '"');
                }

                fclose($handle);

            } else {
                throw new Exception('Cannot open log file "' 
                                        . $logFile 
                                        . '" for writting. (not writable)');
            }

            return true;
        }
    }
}