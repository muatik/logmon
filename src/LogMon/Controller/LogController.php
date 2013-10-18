<?php
namespace LogMon\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class LogController implements ControllerProviderInterface
{
	public function connect(Application $app){
		$log = $app['controllers_factory'];
		$log->get('/', array($this, 'index'));
		$log->get('/entries', array($this, 'getEntries'));
		return $log;
	}

	public function index(Application $app){
		$request = $app['request'];
		print_r($request->getContent());
		return 'log index';
	}

	public function getEntries(Application $app){
		$request = $app['request'];
		$readerManager = new \LogMon\LogReader\Manager();
		$projectManager = $app['projects'];
		$projects = $projectManager->getAll();
		foreach ($projects as $project) {
			echo $project->codeName."<br>\n";
			$reader  = $readerManager->buildReader($project->logConfig);
			$c = $reader->fetch();
			foreach($c as $i)
				print_r($i);
		}
		return 'getEntries';
	}
}

?>
