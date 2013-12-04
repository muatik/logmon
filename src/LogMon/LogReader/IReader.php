<?php
namespace LogMon\LogReader;

interface IReader
{
	public function __construct(\LogMon\LogConfig\IConfig $logConfig);

	/**
	 * sets searching filter
	 * This filter ensures that each log entries in the result 
	 * contains the given keyword.
	 *
	 * @param string $keyword 
	 * @access public
	 * @return void
	 */
	public function filterBySearching($keyword);

	/**
	 * sets log level filter
	 * for example: warning, error, debug, notice etc.
	 * 
	 * @param string $keyword 
	 * @access public
	 * @return void
	 */
	public function filterByLevel($keyword);
	
	/**
	 * sets date range filter
	 * 
	 * @param array $beginDate array('greatherThan' => 'YYYY-mm-dd HH:ii:ss', 'lowerThan' => 'YYYY-mm-dd HH:ii:ss')
	 * @access public
	 * @return void
	 */
	public function filterByDateRange($range);

	/**
	 * resest all filters
	 * 
	 * @access public
	 * @return void
	 */
	public function resetFilters();

	/**
	 * returns all filters
	 * Array(
	 *   'search' => '...',
	 *   'level' => '...',
	 *   'date' => Array(
	 *     'greatherThan' => '...',
	 *     'lowerThan' => '...'
	 *   )
	 * )
	 *
	 * @access public
	 * @return Array
	 */
	public function getFilters();

	/**
	 * specifies the maxiumum amount of log entiries for each fetching
	 * 
	 * @param int $limit 
	 * @access public
	 * @return void
	 * @throws \InvalidArgumentException if limit <1 or limit > 100
	 */
	public function setLimit($limit);
	
	/**
	 * returns limit value
	 * 
	 * @access public
	 * @return int
	 */
	public function getLimit();

	/**
	 * fetchs log entries from source
	 * 
	 * @access public
	 * @return Array
	 */
	public function fetch();
}
