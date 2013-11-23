<?php
namespace LogMon\Tests;
require __DIR__ . '/../../../vendor/autoload.php';

use Silex\WebTestCase;

class ProjectTest extends WebTestCase
{
	public function createApplication()
	{
		$app_env = 'test';
		require __DIR__ . '/../../../resources/config/default.php';
		$app = require __DIR__ . '/../../../src/app.php';
		return $app;
	}

	public function testRegiterProject() 
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}


	public function testDeleteProject()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}


	public function testUpdateProject()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}


	public function testGetProject()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}


	public function testGetAllProjects()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}


	/*
	public function testGettingProjectList()
	{
		$client = $this->createClient();
		$client->request('GET', '/projects');
		$response = $client->getResponse()->getContent();
		$projects = json_decode($response);
		$this->assertTrue(is_array($projects));
	}*/
}
