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

    /**
     * The FilterChain class provides methods for adding multiple Filters into
     * a single filter() call.
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  FilterChain
     */
    class FilterChain
    {
        /**
         * An array of Filter objects.
         *
         * @access protected
         * @var array
         */
        protected $_filters = array();

        /**
         * Setter for @link $_filters
         *
         * @access public
         * @author Jon Matthews
         * @param \MvcFramework\Filter\FilterInterface $filter
         * @return FilterChain
         */
        public function addFilter(\MvcFramework\Filter\FilterInterface $filter)
        {
            $this->_filters[] = array(
                'instance' => $filter
            );
            return $this;
        }

        /**
         * Getter for @link $_filters
         *
         * @access public
         * @author Jon Matthews
         * @return array
         */
        public function getFilters()
        {
            return $this->_filters;
        }

        /**
         * The Filter method. Calls each of the Filter's filter() method
         * from @link $_filters
         *
         * @access public
         * @author Jon Matthews
         * @param mixed $value
         * @return mixed
         */
        public function filter($value)
        {
            if (empty($this->_filters)){
                throw new Exception('No filters set. Set
                        with \MvcFramework\Filter\FilterChain::addFilter()');
            }

            foreach ($this->_filters as $element) {

                $filter = $element['instance'];

                $value = $filter->filter($value);
            }

            return $value;
        }
    }
}