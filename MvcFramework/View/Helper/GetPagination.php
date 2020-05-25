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
     * The GetPagination view helper provides methods for calculating and
     * loading a view partial for pagination.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetPagination extends HelperAbstract
    {
        /**
         * Calculated the pagination variables and loads the view partial.
         *
         * @access public
         * @author Jon Matthews
         * @param int $perPage How many records are being displayed per page?
         * @param int $page The page number of the page we're on. Starting at 0.
         * @param int $total The total number of records there are.
         * @param int $numberEitherSide The number of pages to display either 
         *                              side of the current page.
         * @param array $viewParams The parameters to pass to the link constructor
         * @param string $htmlClass A class name for the 
         * @param string $viewPath
         * @return string
         */
        public function getPagination($perPage = 0,
                                        $page = 0,
                                        $total = 0,
                                        $numberEitherSide = 5,
                                        $viewParams = array(),
                                        $htmlClass = null,                                        
                                        $viewPath = 'paginator-controls')
        {
            if (0 == $total){
                return null;
            }
            
            $last = ceil($total / $perPage);
        
            $first = false;
            if ($page != 1){
                $first = 1;
            }
            
            $previous = false;
            if ($page != 1){
                $previous = ($page - 1);
            }
            
            $next = false;
            if ($page != $last){
                $next = ($page + 1);
            }
            
            if ($total <= $perPage){
                return null;
            }
            
            return $this->getView()->getPartial($viewPath,
                                            array('perPage'             => $perPage,
                                                    'page'              => $page,
                                                    'total'             => $total,
                                                    'previous'          => $previous,
                                                    'next'              => $next,
                                                    'first'             => $first,
                                                    'last'              => $last,
                                                    'viewParams'        => $viewParams,
                                                    'numberEitherSide'  => $numberEitherSide,
                                                    'htmlClass'         => $htmlClass));
        }
    }
}