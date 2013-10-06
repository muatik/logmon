<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

require_once __DIR__ . '/../resources/config/default.php';

$app = require __DIR__ . '/../src/app.php';


$app->get('/test/insert', function(Application $app){

	$r=array();
	$p = new \stdCLass();
	$p->_id = '1';
	$p->name = 'mustafa';
	$p->codeName = 'mustafa atik';
	$p->logConfig= 'test';
	$r[]=$p;

	$p = new \stdCLass();
	$p->_id = '2';
	$p->name = 'sümeyye';
	$p->codeName = 'sümeyye atik';
	$p->logConfig= 'test';
	$r[]=$p;

	$p = new \stdCLass();
	$p->_id = '3';
	$p->name = 'ersin';
	$p->codeName = 'ersin sülük';
	$p->logConfig= 'test';
	$r[]=$p;

	$p = new \stdCLass();
	$p->_id = '4';
	$p->name = 'ali';
	$p->codeName = 'ali şahin';
	$p->logConfig= 'test';
	$r[]=$p;

	$a='';
	foreach($r as $p) {
		$subRequest = Request::create(
			'/projects/register', 'POST', 
			array(), // parameters
			array(), // cookies
			array(), // files
			array(), // server
			json_encode($p)
		);
		$a.= $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
	}
	return $a;
});
$app->get('/test/update', function(Application $app){

	$p = new \stdCLass();
	$p->_id = "4";
	$p->name = 'kamiwlf';
	$p->codeName = 'kaamil aşahin44';
	$p->logConfig= 'test';

	$subRequest = Request::create(
		'/projects/update', 'POST', 
		array(), // parameters
		array(), // cookies
		array(), // files
		array(), // server
		json_encode($p)
	);
	return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
});

$app->run();
