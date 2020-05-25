<?php

/**
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
    use \MvcFramework\Registry\Registry;

    /**
     * The SubStr class provides a method for substring filtering.
     *
     * @category    MvcFramework
     * @package     View
     * @subpackage  Helper
     */
    class SubStr extends HelperAbstract
    {
        /**
         * Performs a substring.
         *
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function subStr($string, 
                                $subStrStart = 0,
                                $subStrLimit = 30,
                                $alwaysDotDotDot = false,
                                $dotdotdot = '&hellip;')
        {
            $filter = new \MvcFramework\Filter\SubString;

            $filter->subStrStart        = $subStrStart;
            $filter->subStrLimit        = $subStrLimit;
            $filter->alwaysDotDotDot    = $alwaysDotDotDot;
            $filter->dotdotdot          = $dotdotdot;

            return $filter->filter($string);
        }
    }
}