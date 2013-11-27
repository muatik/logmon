<?php
namespace LogMon\Tests\LogConfig;

use LogMon\LogConfig\FieldMapper;

class FieldMapperTest extends \PHPUnit_Framework_TestCase
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
	public function testSetFieldMapping($fm)
	{
		$this->assertTrue($this->mapper->fromJson($fm['mapping']));
	}

	/**
	 * @depends testSetFieldMapping
	 * @dataProvider providerFieldMapping
	 */
	public function testGetFieldMapping($fm)
	{
		$this->mapper->fromJson($fm['mapping']);
		$mapped = json_decode($this->mapper->toJson());
		$this->assertEquals($mapped, $fm['mapping']);
	}	

	/**
	 * @depends testSetFieldMapping
	 * @dataProvider providerFieldMapping
	 */
	public function testMap($fm)
	{
		$this->mapper->fromJson($fm['mapping']);
		$mapped = $this->mapper->map($fm['data']['raw']);
		$this->assertEquals($mapped, $fm['data']['mapped']);
	}	

	/**
	 * @depends testSetFieldMapping
	 * @dataProvider providerFieldMapping
	 */
	public function testValidation($fm)
	{
		$this->mapper->fromJson($fm['mapping']);
		$this->mapper->validate();
	}	
}
