<?php
namespace LogMon\Tests\LogReader;

use LogMon\LogReader;
use LogMon\LogConfig;

class ReaderLocalFileTest extends \PHPUnit_Framework_TestCase
{
	
	
	public static $connectionFactory = 'db.mongodb.getConnection';
	public static $app;

	public static function setUpBeforeClass()
	{
		require ROOT . '/resources/config/default.php';
		self::$app = require ROOT . '/src/app.php';
	}

	public function setUp()
	{
		$configSets = (object) array(
			'filePath' => '/var/log/apache2/error.log',
			'fieldMapper' => (object) array(
				'unique' => (object) array('fieldName' => '', 'regex' => '(\d{4})'),
				'date' => (object) array('fieldName' => '', 'regex' => '(\d{4})'),
				'level' => (object) array('fieldName' => '', 'regex' => '(.*)'),
				'message' => (object) array('fieldName' => '', 'regex' => '(.*)')
			)
		);

		$logConfig = new LogConfig\ConfigLocalFile();
		$logConfig->fromJson($configSets);
		$this->reader = new LogReader\ReaderLocalFile($logConfig);
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

	public function providerLogLevel()
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
			array(array('greaterThan' => '', 'lowerThan' => '')),
			array(array('greaterThan' => '2013-12-16 22:20:00', 'lowerThan' => '')),
			array(array('greaterThan' => '2013-12-16 22:20:00', 'lowerThan' => '2013-12-17 18:10:00')),
			array(array('greaterThan' => '', 'lowerThan' => '2013-12-17 18:10:00'))
		);
	}

	public function providerLimitException()
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
		$this->reader->filterByDateRange($range);
		$filters = $this->reader->getFilters();
		$this->assertEquals($filters['date'], $range);
	}

	public function testResetFilter()
	{
		$this->reader->filterBySearching('a search term');
		$this->reader->resetFilters();
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
	 * @expectedException \InvalidArgumentException
	 */
	public function testSetLimitException($limit)
	{
		$this->reader->setLimit($limit);
	}


	public function testReading()
	{
		$entries = $this->reader->fetch();
	}


}


