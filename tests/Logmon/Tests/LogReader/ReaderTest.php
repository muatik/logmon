<?php
namespace LogMon\Tests\LogReader;

use LogMon\LogReader;
use LogMon\LogConfig;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
	
	
	public static $connectionFactory = 'db.mysql.getConnection';
	public static $app;

	public static function setUpBeforeClass()
	{
		require ROOT . '/resources/config/default.php';
		self::$app = require ROOT . '/src/app.php';
	}

	public function setUp()
	{
		$configSets = (object) array(
			'host' => 'localhost',
			'port' => '3306',
			'charset' => 'utf8',
			'username' => 'root',
			'password' => 'root',
			'databaseName' => 'test',
			'collectionName' => 'logTable1',
			'fieldMapper' => (object) array(
				'unique' => (object) array('fieldName' => 'id', 'regex' => '(.*)'),
				'date' => (object) array('fieldName' => 'date', 'regex' => '(.*)'),
				'level' => (object) array('fieldName' => 'level', 'regex' => '(.*)'),
				'message' => (object) array('fieldName' => 'text', 'regex' => '(.*)')
			)
		);

		$factory = self::$app[self::$connectionFactory];
		$logConfig = new LogConfig\ConfigMysql($factory);
		$logConfig->fromJson($configSets);
		$this->reader = new LogReader\ReaderMysql($logConfig);
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
			array(array('greaterThan' => 'x', 'lowerThan' => 'x')),
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

}

