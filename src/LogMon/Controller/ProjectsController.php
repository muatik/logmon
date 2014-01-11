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
			
			$logConfig = new \LogMon\LogConfig\ConfigMysql($app['db.mysql.getConnection'], json_encode(array(
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
			$logConfig2 = new \LogMon\LogConfig\ConfigMongodb($app['db.mongodb.getConnection'], json_encode(array(
				'host' => 'localhost',
				'port' => '3306',
				'charset' => 'utf8',
				'username' => 'root',
				'password' => 'root',
				'auth' => false,
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
		$response = new \LogMon\Response();
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

			$projects->register($newProject);
			$response->setStatus(10, 'Project has just been registered.');
			$response->setData('project', (object) $newProject->toJson(false));
			$response->setData('project->logConfig', 
				$newProject->logConfig->toJson(false)); 
		} catch (\Exception $e) {
			$response->setStatus(11, 'Project could not be registered. ' 
				. $e->getMessage());
		}

		return $app->json($response);
	}


	public function delete(Application $app, $id) 
	{
		$response = new \LogMon\Response();
		$projects = $app['projects'];
		$newProject = $app['project.factory'];
		
		try {
			$projects->deleteById($id);
			$response->setStatus(13, 'Project hast just been deleted permanently.');
			$response->setData('project->_id', $id);
		} catch (\Exception $e) {
			$response->setStatus(12, 'Project could not be deleted. '. $e->getMessage());
		}
		
		return $app->json($response);
	}

	
	public function update(Application $app) 
	{
		$response = new \LogMon\Response();
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

			$newProject->fromJson($jsonProject);
			$projects->update($newProject);
			$response->setStatus(14, 'Project has just been updated.');
			$response->setData('project', (object) $newProject->toJson(false));
			$response->setData('project->logConfig', 
				$newProject->logConfig->toJson(false)); 
		} catch(\Exception $e) {
			$response->setStatus(16, 'Project could not be updated. ' 
				. $e->getMessage());
		}

		return $app->json($response);
	}


	public function getList(Application $app) 
	{
		$response = new \LogMon\Response();
		$projects = $app['projects'];

		try {
			$records = $projects->getAll();
			$projectList = array();
			foreach($records as $i) {
				$i = $i->toJson(false);
				$i['_id'] = (string) $i['_id'];
				$i['logConfig'] =$i['logConfig']->toJson(false);
				$projectList[] = $i;
			}
			$response->setData('projects', $projectList);
		} catch (\Exception $e) {
			$response->setStatus(16, 'Projects could not be listed. '
			 . $e->getMessage());
		}

		return $app->json($response);
	}


}
