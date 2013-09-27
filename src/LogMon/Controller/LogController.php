<?php
namespace LogMon\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class LogController implements ControllerProviderInterface
{
	public function connect(Application $app){
		$log = $app['controllers_factory'];
		$log->get('/', array($this, 'index'));
		$log->get('entries', array($this, 'getEntries'));
		return $log;
	}

	public function index(Application $app){
		$request = $app['request'];
		print_r($request->getContent());
		return 'log index';
	}

	public function getEntries(Application $app){
		$request = $app['request'];
		echo $request->get('ads', 'elam');
		print_r($request->query->all());
		return 'getEntries';
	}
}

?>
