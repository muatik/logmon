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
			->method('PUT|GET');

		$index->get('/list', array($this, 'getList'));

		return $index;
	}

	public function index(Application $app) {
		return 'projects index';
	}

	public function register(Application $app) {
		$request = $app['request'];
		$projects= $app['projects'];
		$project = json_decode($request->getContent());
		
		try {
			$project = $projectss::create($project);
			$project->save();
		} catch(Exception $e) {

		}
		/*
		$errors = $validator->validateValue($project->name, 
			array(
				new Assert\NotBlank(),
				new Assert\Length(array('min' => 3))
			));

		if (count($errors) > 0) {
			return $errors;
		}

		print_r($project);
		return 'registering...';
		 */
	}

	public function getList(Application $app) {
		return 'getList';
	}
}


?>
