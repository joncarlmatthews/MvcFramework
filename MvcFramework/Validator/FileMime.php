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
    
    use finfo;
    
    class FileMime extends ValidatorAbstract
    {
        /**
         * Invalid mime message key.
         */
        const INVALID_MIME = 'invalidMime';
        
        /**
         * Error message templates.
         * @var mixed
         */
        protected $_messageTemplates = array(
            self::INVALID_MIME      => 'File type supplied is invalid - valid types:%s.',
        );
        
        /**
         * Error message template variables.
         * @var mixed
         */
        protected $_messageVariables = array(
            'fileTypes' => array('options' => 'fileTypes')
        );
        
        /**
         * Constructor override to concatenate the error string
         * @param array $options 
         */
        public function __construct(array $options = array())
        {
            parent::__construct($options);
            $this->_messageTemplates[self::INVALID_MIME] = sprintf($this->_messageTemplates[self::INVALID_MIME], $this->fileTypeString());
        }        
        
        /**
         * Checks for a valid file mime
         * @param mixed $value 
         * @throws MvcFramework\Exception\Exception 
         * @return mixed
         */
        public function isValid($value)
        {
            if(isset($this->_options['fileTypes'])) {
                $fileTypes = (array)$this->_options['fileTypes'];
            }
            
            if(false === $fileTypes) {
                throw new Exception('No filetypes to check values against');
            }
            
            foreach($fileTypes as $type) {
                if($this->_mime_content_type($value) === $type) {
                    return true;
                }
            }            
            
            $this->_addMessage(self::INVALID_MIME);
            return false;
        }
        
        /**
         * Implementation of mime_content_type with finfo
         * @param mixed $filename 
         * @return mixed
         */
        private function _mime_content_type($filename)
        {
            $result = new finfo();
            
            return $result->file($filename, FILEINFO_MIME_TYPE);
        }        
        
        /**
         * Concatenates passed file types
         * @return mixed
         */
        private function fileTypeString() {
            $returnString = '';
            if(isset($this->_options['fileTypes'])) {
                $fileTypes = (array)$this->_options['fileTypes'];
            }
            
            foreach($fileTypes as $type) {
                $returnString .= ' ' . $type;
            }
            
            return $returnString;
        }
    }
}