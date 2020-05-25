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
    /**
     * The GetPaginationSliders returns an array of "sliding pagination" pages
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetPaginationSliders extends HelperAbstract
    {
        /**
         * Calculated the pagination variables and loads the view partial.
         *
         * @access public
         * @author Jon Matthews
         * @param int $activePage
         * @param int $totalPages
         * @param array $viewParams
         * @return array
         */
        public function getPaginationSliders($activePage = 0,
                                                $totalPages = 0,
                                                $numberEitherSide,
                                                $viewParams = array())
        {
            if ($totalPages <= 1) {
                return;
            }
            
            /*
            echo "Number Either Side:";
            echo $numberEitherSide;
            echo '<br />';
            */

            // Calculate the active page

            /*
            echo "Active page:";
            echo $activePage;
            echo '<br />';
            */

            // Calculate the page start
            $pageStart = ($activePage - $numberEitherSide);
            if ($pageStart <= 0) {
                $pageStart = 1;
            }

            // Calculate the page end
            $pageEnd = ($activePage + $numberEitherSide);
            if ($pageStart <= 0) {
                $pageStart = 1;
            }
            if (1 == $pageStart) {
                $pageEnd = ($numberEitherSide * 2) + 1;
            }
            if ($pageEnd > $totalPages) {
                $pageEnd = $totalPages;
            }
            if (($pageEnd - $activePage) < $numberEitherSide) {
                $theDifference = ($pageEnd - $activePage);
                $theDifference = ($numberEitherSide - $theDifference);
                /*
                echo "The difference:";
                echo $theDifference;
                echo '<br />';
                */
            }
            if (isset($theDifference)) {
                $pageStart = ($pageStart - $theDifference);
                if ($pageStart <= 0) {
                    $pageStart = 1;
                }
            }

            /*
            echo "Page start:";
            echo $pageStart;
            echo '<br />';
            */

            /*
            echo "Page end:";
            echo $pageEnd;
            echo '<br />';
            */

            $pages = array();
            
            for ($i = $pageStart; $i <= $pageEnd; $i++) {

                $url = $this->getView()->url(array_merge(array('page' => $i), $viewParams));
                
                $pages[$i] = $url;

            }
            return $pages;
        }
    }
}