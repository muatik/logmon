<?php
namespace LogMon\LogReader;

abstract class Reader
{

	/**
	 * logConfig 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $logConfig;

	/**
	 * resource connection 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $connection;

	/**
	 * initializes the resource connection
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $isInitialized;

	/**
	 * represents the maxiumum amount of log entiries in each fetching
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
		$this->logConfig = $logConfig;
	}

	public function initialize()
	{
		$this->connection = $this->logConfig->getConnection();
	}
}
