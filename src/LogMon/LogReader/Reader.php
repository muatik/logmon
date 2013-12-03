<?php
namespace LogMon\LogReader;

abstract class Reader
{

	/**
	 * an object of log configuration by which this class will 
	 * read log entries.
	 * 
	 * @var LogMon\LogConfig\IConfig
	 * @access protected
	 */
	protected $logConfig;

	/**
	 * log filters
	 * 
	 * @var array
	 * @access protected
	 */
	protected $filters = array();

	/**
	 * specifiy the maxiumum amount of log entiries for each fetching
	 * 
	 * @var int
	 * @access protected
	 */
	protected $limit = 100;

	protected $filter = array(
		'type',
		'contains',
		'after',
		'before'
	);

	public function __construct(\LogMon\LogConfig\IConfig $logConfig)
	{
		$this->resetFilters();
		$this->logConfig = $logConfig;
	}


	/**
	 * @implements IReader
	 */
	public function filterBySearching($keyword)
	{
		if (!is_string($keyword))
			throw new \InvalidArgumentException(sprintf(
				"Search term can only be a string. '%s' was given.", $keyword
			));

		$this->filters['search'] = $keyword;
	}

	/**
	 * @Implements IReader
	 */
	public function filterByLevel($level)
	{
		if (!is_string($level))
			throw new \InvalidArgumentException(sprintf(
				"Log level can only be a string. '%s' was given.", $level
			));

		$this->filters['level'] = $level;
	}

	/**
	 * @Implements IReader
	 */
	public function filterByDateRange($range)
	{
		if (isset($range['greatherThan'], $range['lowerThan']))
			throw new \InvalidArgumentException(sprintf(
				"Date range can only be an array such as Array('greaterThan' => "
				. "'YYYY-mm-dd HH:ii:ss', 'lowerThan' => 'YYYY-mm-dd HH:ii:ss'). "
				. "'%s' was given", (string) $range
			));

		$this->filters['date'] = $range;
	}

	/**
	 * @Implements IReader
	 */
	public function resetFilters()
	{
		$this->filters = array(
			'search' => null, // string
			'level' => null, // string
			'date' => array(
				'greatherThan' => null, // string (YYYY-mm-dd HH:ii:ss)
				'lowerThan' => null // string (YYYY-mm-dd HH:ii:ss)
			)
		);
	}

	/**
	 * @Implements IReader
	 */
	public function getFilters()
	{
		return $this->filters;
	}
}
