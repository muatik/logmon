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
	 * the connection of log resource
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $connection;

	/**
	 * indicates whether the class is initialized or not
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $isInitialized = false;

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
		$this->logConfig = $logConfig;
	}

	/**
	 * initializes necesssary resources for essentail functions
	 * 
	 * @access public
	 * @return void
	 */
	public function initialize()
	{
		$this->logConfig->test();
		$this->connection = $this->logConfig->getConnection();
	}
}
