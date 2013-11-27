<?php
namespace LogMon\Tests\LogConfig;

class LogConfigLocalFileTest extends BaseLogConfigTest
{
	public $logConfigClass = '\LogMon\LogConfig\ConfigLocalFile';
	public static $connectionFactory = '';
	public static $app;

	public function providerConfig()
	{
		$configSets = array(
			array(
				'filePath' => '/tmp/ConfigFileTestCase.txt',
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

	public function setUp()
	{
		$this->logConfig = new $this->logConfigClass();
	}

	/**
	 * @dataProvider providerConfig
	 */
	public function testSetterGetter($config)
	{
		$this->logConfig->setFilePath('/tmp/test1.txt');
		$this->assertEquals($this->logConfig->filePath, '/tmp/test1.txt');
	}
}
