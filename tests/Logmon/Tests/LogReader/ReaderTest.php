<?php
namespace LogMon\Tests\LogReader;

use LogMon\LogReader;
use LogMon\LogConfig;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
	
	public function setUp()
	{
		$this->reader = new ReaderTextFile();
	}

	public function providerKeywords()
	{
		return array(
			array('disk not %100'),
			array('could not connect'),
			array('authentication'),
			array("operation 'commit' not allowed")
		);
	}

	public function providerLogLevel
	{
		return array(
			array('debug'),
			array('notice'),
			array('warning'),
			array('error')
		);
	}

	public function providerDataRange()
	{
		return array(
			array('greaterThan' => null, 'lowerThan' => null),
			array('greaterThan' => '2013-12-16 22:20:00', 'lowerThan' => null),
			array('greaterThan' => '2013-12-16 22:20:00', 'lowerThan' => '2013-12-17 18:10:00'),
			array('greaterThan' => null, 'lowerThan' => '2013-12-17 18:10:00')
		);
	}

	public function poviderLimitException()
	{
		// anything except integer
		return array(
			array('word'),
			array(12.4),
			array(false),
			array(-5),
			array(105)
		);
	}

	/**
	 * @dataProvider providerKeywords
	 */
	public function testFilterBySearching($keyword)
	{
		$this->reader->filterBySearching($keyword);
		$filters = $this->reader->getFilters();
		$this->assertEquals($filters['search'], $keyword);
	}

	/**
	 * @dataProvider providerLogLevel
	 */
	public function testFilterByLevel($logLevel)
	{
		$this->reader->filterByLevel($logLevel);
		$filters = $this->reader->getFilters();
		$this->assertEquals($filters['level'], $logLevel);
	}

	/**
	 * @dataProvider providerDataRange
	 */
	public function testFilterByDateRange($range)
	{
		$this->reader->filterByDateRange($range['greaterThan'], $range['lowerThan']);
		$filters = $this->reader->getFilters();
		$this->assertEquals($filters['date'], $range);
	}

	public function testResetFilter()
	{
		$this->reader->filterBySearching('a search term');
		$this->reader->resetFilter();
		$filters = $this->reader->getFilters();
		$this->assertEquals($filters['search'], null);
	}

	public function testSetLimit()
	{
		$this->reader->setLimit(45);
		$this->assertEquals($this->reader->getLimit(), 45);
	}

	/**
	 * @dataProvider providerLimitException
	 * @exception \InvalidArgumentException
	 */
	public function testSetLimitException($limit)
	{
		$this->reader->setLimit($limit);
	}

}

