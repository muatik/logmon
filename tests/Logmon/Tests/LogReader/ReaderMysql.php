<?php
namespace LogMon\Tests\LogReader;

use LogMon\LogReader;
use LogMon\LogConfig;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
	
	public function setUp()
	{
		$this->mapper = new FieldMapper();
	}

	public function providerFieldMapping()
	{
		$fm1 = array(
			'data' => array(
				'raw' => array('id' => 4, 'reportTime'=> '2013-12-15 16:12:10', 'debugCtg' => 'warning', 'logMessage' => 'This is a nice log entry.'),
				'mapped' => array('unique' => 4, 'date'=> '2013-12-15 16:12:10', 'type' => 'warning', 'message' => 'This is a nice log entry.')
			),
			'mapping' => (object) array(
				'unique' => (object) array('fieldName'=>'id', 'regex' => '(.*)'),
				'type' => (object) array('fieldName'=>'debugCtg', 'regex' => '(.*)'),
				'date' => (object) array('fieldName'=>'reportTime', 'regex' => '(.*)'),
				'message' => (object) array('fieldName'=>'logMessage', 'regex' => '(.*)')
			)
		);
		$fm2 = array(
			'data' => array(
				'raw' => array('line' => '4 2013-12-15 16:12:10 warning This is a nice log entry.'),
				'mapped' => array('unique' => '4', 'date'=> '2013-12-15 16:12:10', 'type' => 'warning', 'message' => 'This is a nice log entry.')
			),
			'mapping' => (object) array(
				'unique' => (object) array('fieldName'=>'line', 'regex' => '(^\d+)'),
				'date' => (object) array('fieldName'=>'line', 'regex' => '^\d+ (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})'),
				'type' => (object) array('fieldName'=>'line', 'regex' => '^\d+ \d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} ([a-z]+)'),
				'message' => (object) array('fieldName'=>'line', 'regex' => '^\d+ \d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} [a-z]+ (.+)')
			)
		);
		return array(array($fm1), array($fm2));
	}


	/**
	 * @dataProvider providerFieldMapping
	 */
	public function testQuery()
	{
		// the latest 30 entries
		$this->reader->query()
			->limit(30)
			->sort('date', 'desc');

		$logEntries = iterator_to_array($this->reader);
		$this->assertEquals($logEntries, $expectedLog);
	}

	public function testQuery2($logConfig, $expectedLog)
	{
		// the latest 20 entries containing given keyword
		$this->reader = new ReaderMysql($logConfig);
	
		$this->reader->query()
			->contains('a search term')
			->limit(20)
			->sort('date', 'desc');

		$logEntries = iterator_to_array($this->reader);
		$this->assertEquals($logEntries, $expectedLog);
	}

	public function testQuery3()
	{
		// the oldest 10 entries, between the given two date
		$this->reader->query()
			->between($timestamp1, $timestamp2)
			->limit(10)
			->sort('date', 'asc');

		$logEntries = iterator_to_array($this->reader);
		$this->assertEquals($logEntries, $expectedLog);
	}

}

