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
    /**
     * The SubString class provides methods for substringing a string 
     * slashes from a string
     *
     * @category    MvcFramework
     * @package     Filter
     */
    class SubString extends \MvcFramework\Filter\FilterAbstract
    {
        public $subStrStart         = 0;
        public $subStrLimit         = 30;
        public $alwaysDotDotDot     = false;
        public $dotdotdot           = '&hellip;';

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
            $value = (string)$value;
            
            if(strlen($value) > $this->subStrLimit){
                $precis = substr($value, $this->subStrStart, $this->subStrLimit);
                if (!$this->alwaysDotDotDot){
                    if($last_space = strrpos($precis, ' ')){
                        $precis = substr($precis, $this->subStrStart, $last_space) . $this->dotdotdot;
                    }
                }else{
                    $precis = $precis . $this->dotdotdot;
                }
            } else {
                $precis = $value;
            }
         
            return $precis;
        }
    }
}