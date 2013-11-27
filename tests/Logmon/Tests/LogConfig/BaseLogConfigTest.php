<?php
namespace LogMon\Tests\LogConfig;

abstract class BaseLogConfigTest extends \PHPUnit_Framework_TestCase
{
	public $logConfigClass = null;
	public static $connectionFactory = 'db.mysql.getConnection';
	public static $app;

	public static function setUpBeforeClass()
	{
		require ROOT . '/resources/config/default.php';
		self::$app = require ROOT . '/src/app.php';
	}

	public function setUp()
	{
		$factory = self::$app[self::$connectionFactory];
		$this->logConfig = new $this->logConfigClass($factory);
	}

	public function providerConfig()
	{
		$configSets = array(
			array(
				'host' => 'localhost',
				'port' => '3306',
				'charset' => 'utf8',
				'username' => 'root',
				'password' => 'root',
				'databaseName' => 'test',
				'collectionName' => 'logTable1',
				'fieldMapping' => (object) array(
					'unique' => (object) array('fieldName' => 'id', 'regex' => '(.*)'),
					'date' => (object) array('fieldName' => 'at', 'regex' => '(.*)'),
					'type' => (object) array('fieldName' => 'type', 'regex' => '(.*)'),
					'message' => (object) array('fieldName' => 'text', 'regex' => '(.*)')
				)
			)
		);

		return array($configSets);
	}


	/**
	 * @dataProvider providerConfig
	 */
	public function testFromJson($config)
	{
		$this->logConfig->fromJson(json_encode($config));
	}


	/**
	 * @depends testFromJson
	 * @dataProvider providerConfig
	 */
	public function testToJson($config)
	{
		$this->logConfig->fromJson(json_encode($config));
		$output = $this->logConfig->toJson();
		$output = (Array) json_decode($output);
		foreach($config as $i => $val)
			$this->assertEquals($output[$i], $val);
	}


	/**
	 * @depends testFromJson
	 * @dataProvider providerConfig
	 */
	public function testToString($config)
	{
		$this->logConfig->fromJson(json_encode($config));
		$output = $this->logConfig->toJson();
		$output = (Array) json_decode($output);
		foreach($config as $i => $val)
			$this->assertEquals($output[$i], $val);
	}


	/**
	 * @dataProvider providerConfig
	 */
	public function testSetterGetter($config)
	{
		$this->logConfig->host = 'localhost';
		$this->logConfig->username= 'superman';
		$this->logConfig->databaseName= 'aboveclouds';

		$this->assertEquals($this->logConfig->host, 'localhost');
		$this->assertEquals($this->logConfig->username, 'superman');
		$this->assertEquals($this->logConfig->databaseName, 'aboveclouds');
	}


	/**
	 * @depends testFromJson
	 * @dataProvider providerConfig
	 */
	public function testValidation($config)
	{
		$this->logConfig->fromJson(json_encode($config));
		$this->assertTrue($this->logConfig->validate());
	}


}
