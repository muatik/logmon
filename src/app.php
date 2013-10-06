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
	$connParams = $app['db.config.mysql'];
	$conn = \Doctrine\DBAL\DriverManager::getConnection($connParams);
	return $conn;
});

$app['db.mongodb'] = $app->share(function($app) {
	$connParams = $app['db.config.mongodb'];
	
	if ($connParams['auth']) {
		$connString = sprintf(
			'mongodb://%s:%s@%s/%s',
			$connParams['user'],
			$connParams['password'],
			$connParams['host'],
			$connParams['database']
		);
	} else {
		$connString = sprintf(
			'mongodb://%s/%s',
			$connParams['host'],
			$connParams['database']
		);
	}
	
	$conn = new \Doctrine\MongoDB\Connection($connString);
	$db = $conn->selectDatabase($connParams['database']);
	return $db;
});

$app['db.mysql.collection'] = function($app) {
	return new LogMon\Manager\MysqlDBCollection($app['db.mysql']);
};

$app['db.mongodb.collection'] = function($app) {
	return new LogMon\Manager\MongoDBCollection($app['db.mongodb']);
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
