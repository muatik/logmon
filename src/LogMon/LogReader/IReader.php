<?php
namespace LogMon\LogReader;

interface IReader
{
	public function __construct(\LogMon\LogConfig\IConfig $logConfig);

	/**
	 * fetchs log entries from source
	 * 
	 * @access public
	 * @return Array
	 */
	public function fetch();
}
