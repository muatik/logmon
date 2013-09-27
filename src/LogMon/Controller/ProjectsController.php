<?php
namespace LogMon\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectsController implements ControllerProviderInterface
{
	public function connect(Application $app){
		$index = $app['controllers_factory'];
		
		$index->get('/', array($this, 'index'));
		
		$index->match('/register', array($this, 'register'))
			->method('POST|GET');

		$index->get('/list', array($this, 'getList'));

		return $index;
	}

	public function index(Application $app) {
		return 'projects index';
	}
	
	public function register(Application $app) {
		$projects = $app['projects'];
		$newProject = $app['project.factory'];

		try	{
			$newProject->initFromJson($app['request']->getContent());
			$projects->register($newProject);
			
			$return = 'ok';
		} catch (\Exception $e) {
			$return = $e->getMessage();
		}

		return $return;
	}


	public function getList(Application $app) {
		$projects = $app['projects'];
		$projectList = $projects->getAll();

		$result = new \stdClass();
		$result->projects = array();

		foreach($projectList as $i) {
			$i = $i->getProperties();
			$i['_id'] = (string) $i['_id'];
			$result->projects[] = $i;
		}	
		
		return json_encode($result);
	}
}


?>
