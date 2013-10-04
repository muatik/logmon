<?php

require ROOT."/vendor/autoload.php";

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LogMon\Manager\MongoDBCollection;

if ($app['debug']) {
	error_reporting(E_ALL);
	ini_set('display_errors','on');
}

if (isset($app)) {
	$appConfig = $app;
	$app = new Silex\Application();
	foreach($appConfig as $k => $v)
		$app[$k] = $v;
	
	// from now, all configurations will be in $app,
	// so we can unset this
	unset($appConfig);
	
} else {
	// any config object is not present.
	$app = new Silex\Application();
}

/**
 * registering services
 * */
$app->register(new Silex\Provider\SessionServiceProvider(), array());

// $app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => $app['logging.file.name'],
	'monolog.name' => 'logmon',
	'monolog.hander' => $app->share(
		function(Application $app) {
			return new Monolog\Handler\MongoDBHandler(
				$app['db.config.mongodb'],
				$app['logging.database'] //database name
			);
		}
	)
));


$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
	'http_cache.cache_dir' => ROOT.'/temp/http'
));

$app['db.mysql'] = $app->share(function($app) {
	$conf = $app['db.config.mysql'];
	return new Mysqli(
		$conf['host'], $conf['user'], $conf['password'], $conf['database']
	);
});

$app['db.mongo'] = $app->share(function($app) {
	$c = new Mongo(); 
	$db = $c->selectDB('logmon');
	return $db;
	// TODO: use config
	return new Mongo($app['db.config.mongodb']);
});

$app['db.mysql.collection'] = function($app) {
	return new LogMon\Manager\MysqlDBCollection($app['db.mysql']);
};

$app['db.mongo.collection'] = function($app) {
	return new LogMon\Manager\MongoDBCollection($app['db.mongo']);
};




$app['projects'] = $app->share(function($app) {
	return new LogMon\Manager\Projects($app['db.mysql.collection']);
});

$app['project.factory'] = function($app) {
	return new LogMon\Model\Project();
};

require ROOT.'src/LogMon/router.php';


return $app;

?>
