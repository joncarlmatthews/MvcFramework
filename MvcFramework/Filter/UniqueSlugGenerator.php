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

namespace MvcFramework\Filter
{
    use \MvcFramework\Filter\Exception;

    use \MvcFramework\Filter\Slug;

    /**
     * The UniqueSlugGenerator class provides methods for generating a unuqie
     * slug.
     *
     * @category    MvcFramework
     * @package     Filter
     */
    class UniqueSlugGenerator extends \MvcFramework\Filter\FilterAbstract
    {
        private $_adapter = null;

        /**
         * Filter method.
         *
         * @access public
         * @author Jon Matthews
         * @param string $value
         * @return string
         */
        public function filter($value)
        {
            // existingSlugs
            if ( !isset($this->_options['existingSlugs']) ){
                throw new Exception('No slugs to check against.');
            }

            if ( !is_array($this->_options['existingSlugs']) ){
                throw new Exception('existingSlugs must be an array.');
            }

            // adapter
            if ( (isset($this->_options['adapter'])) ){

                if (!($this->_options['adapter'] instanceof \MvcFramework\Filter\FilterAbstract)){
                    throw new Exception('adapter is not a valid Filter class.');
                }else{
                    $this->_adapter = $this->_options['adapter'];
                }

            }else{
                $this->_adapter = new \MvcFramework\Filter\Slug;
            }              

            return $this->_generateUniqueSlug($this->_options['existingSlugs'],
                                                $value);
        }

        private function _generateUniqueSlug(array $existingSlugs, $name, $attempt = 0)
        {
            $debug = false;
            
            // Set the maximum character length of the sring to be passed to the
            // slug filter.
            $maxLen = $this->_adapter->getMaxLength();
            
            // Create a variable holding the un-modified original name value for 
            // use later.
            $originalName = $name;
            
            // Set a variable holding the attempt number.
            $attempt = (int)$attempt;
            
            // Is this an attempt at uniqueness?
            if ($attempt >= 1){
                
                // Set the name to be the name plus the attempt number.
                $name = $name . ' ' . $attempt;
                
                // Is this concatenation greater than the maximum length allowed?
                if (strlen($name) >= $maxLen){
                    
                    // Calculate the number of characters to remove off of the end
                    // of the original string.
                    $numCharsToRemove = ( (strlen($originalName)) - (strlen($attempt)) - 1 );
                    
                    // Truncate the string.
                    $name = substr($originalName, 0, $numCharsToRemove);
                    
                    // Re concatenate the new shorter string with the attempt number.
                    $name = $name . ' ' . $attempt;
                }
                
            }else{
                
                // ...this isnt an attempt at uniqueness.
                
                // Is the length of name greater than the maximum length allowed?
                if (strlen($name) >= $maxLen){
                    $name = substr($name, 0, $maxLen);
                }
                
            }
            
            // Debug:
            if ($debug){
                echo '<hr>';
                echo 'Original Name = ' . $originalName;
                echo ' (' . strlen($originalName) . ')';
                echo '<br />';
                echo 'Modified Name = ' . $name;
                echo ' (' . strlen($name) . ')';
                echo '<br />';
            }
            
            // Create a slug filter object.
            $slug = $this->_adapter->filter($name);
            
            // Debug:
            if ($debug){
                echo 'Slug = ' . $slug;
                echo '<br />';
            }
            
            // Is the slug already in the array?
            if (in_array($slug, $existingSlugs)){
                
                // ...yes.
                
                // Set the next attempt number to be this attempt plus 1.
                $nextAttemptNum = ($attempt + 1);
                
                // Debug:
                if ($debug){
                    echo 'EXISTS: ';
                    echo $slug . '(' . $name . ')';
                    echo '<br />';
                    echo 'Appending a ' . $nextAttemptNum . '...';
                    echo '<br />';
                }
                
                // Retry the slug generation.
                $slug = $this->_generateUniqueSlug($existingSlugs, 
                                                    $originalName, 
                                                    $nextAttemptNum);
            }
            
            return $slug;
        }
    }
}