<?php
namespace LogMon\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectsController implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$index = $app['controllers_factory'];
		$index->get('/', array($this, 'getList'));
		$index->put('/', array($this, 'register'));
		$index->post('/{id}', array($this, 'update'));
		$index->delete('/{id}', array($this, 'delete'));
		$index->get('/log/entries', function(Application $app) {
			
			$logConfig = new \LogMon\LogConfig\ConfigMysql($app, json_encode(array(
				'host' => 'localhost',
				'port' => '3306',
				'charset' => 'utf8',
				'username' => 'root',
				'password' => 'root',
				'databaseName' => 'test',
				'collectionName' => 'logTable1',
				'fieldMapping' => array(
					'unique' => array('fieldName' => 'id', 'regex' => '(.*)'),
					'date' => array('fieldName' => 'at', 'regex' => '(.*)'),
					'type' => array('fieldName' => 'type', 'regex' => '(.*)'),
					'message' => array('fieldName' => 'text', 'regex' => '(.*)')
				)
			)));
			$logConfig2 = new \LogMon\LogConfig\ConfigMysql($app, json_encode(array(
				'host' => 'localhost',
				'port' => '3306',
				'charset' => 'utf8',
				'username' => 'root',
				'password' => 'root',
				'databaseName' => 'test',
				'collectionName' => 'logTable2',
				'fieldMapping' => array(
					'unique' => array('fieldName' => 'text', 'regex' => '(^\d)+'),
					'type' => array('fieldName' => 'text', 'regex' => '^\d+ (\w+) '),
					'date' => array('fieldName' => 'text', 'regex' => '^\d+ \w+ (\d{4}-\d{2}-\d{2} \d{2}:\d{2})'),
					'message' => array('fieldName' => 'text', 'regex' => '^\d+ \w+ \d{4}-\d{2}-\d{2} \d{2}:\d{2} (.+)'),
				)
			)));

			$reader = \LogMon\LogReader\Manager::BuildReader($logConfig);
			$cursor = $reader->fetch();

			foreach ($cursor as $i)
				echo sprintf("id: %s, type: %s, message: %s, date: %s <br>", 
					$i['unique'], $i['type'], $i['message'], $i['date']
				);
			return 'OK';
		});
		return $index;
	}

	public function register(Application $app) 
	{
		$projects = $app['projects'];
		$newProject = $app['project.factory'];

		try	{
			$jsonProject = json_decode($app['request']->getContent());
			
			if (!isset($jsonProject->logConfig))
				throw new \Exception("The paramter 'logConfig' not found in the request.");

			if (!is_object($jsonProject->logConfig))
				throw new \Exception("The paramter 'logConfig' is not valid.");

			$jsonProject->logConfig = \LogMon\LogConfig\Manager::build(
				$app, 
				$jsonProject->logConfig
			);

			$newProject->fromJson($jsonProject);

			// TODO: return more appropriate return message
			$projects->register($newProject);
			$return = 'registered';
		} catch (\Exception $e) {
			$return = $e->getMessage();
		}

		return $return;
	}

	public function delete(Application $app, $id) 
	{
		$projects = $app['projects'];
		$newProject = $app['project.factory'];
		try {
			$projects->deleteById($id);
			$return = 'deleting '.$id;
		} catch (\Exception $e) {
			$return = $e->getMessage();
		}
		return $return;
	}

	public function update(Application $app) 
	{
		$projects = $app['projects'];
		$newProject = $app['project.factory'];

		try {
			$jsonProject = json_decode($app['request']->getContent());
			if (!isset($jsonProject->logConfig))
				throw new \Exception("The paramter 'logConfig' not found in the request.");

			if (!is_object($jsonProject->logConfig))
				throw new \Exception("The paramter 'logConfig' is not valid.");

			$jsonProject->logConfig = \LogMon\LogConfig\Manager::build(
				$app, 
				$jsonProject->logConfig
			);
			
			$newProject->initFromObject($jsonProject);
			$projects->update($newProject);
			// TODO: return more appropriate return message
			$return = 'updated';
		} catch(\Exception $e) {
			$return = $e->getMessage();
		}

		return $return;
	}

	public function getList(Application $app) 
	{
		$projects = $app['projects'];

		try {
			$projectList = $projects->getAll();
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		$result = new \stdClass();
		$result->projects = array();

		foreach($projectList as $i) {
			$i = $i->toJson(false);
			$i['_id'] = (string) $i['_id'];
			$i['logConfig'] =$i['logConfig']->toJson(false);
			$result->projects[] = $i;
		}	
		
		return json_encode($result);
	}
}
