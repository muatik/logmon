<?php
namespace LogMon\LogReader;

abstract class Reader
{
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
}
