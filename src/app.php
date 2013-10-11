<?php

require ROOT."/vendor/autoload.php";

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LogMon\Manager\MongoDBCollection;


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
if ($app['debug']) {
	error_reporting(E_ALL);
	ini_set('display_errors','on');
	$app->register(new \Whoops\Provider\Silex\WhoopsServiceProvider);
}

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

$app['db.mysql.getConnection'] = function($app) {
	return function($connParams) {
		return \Doctrine\DBAL\DriverManager::getConnection($connParams);
	};
};

$app['db.mongodb.getConnection'] = function($app) {
	return function($connParams) {

		if (isset($connParams['auth']) && $connParams['auth']) {
			$connString = sprintf(
				'mongodb://%s:%s@%s/%s',
				$connParams['username'],
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

		return new \Doctrine\MongoDB\Connection($connString);
	};
};

$app['db.mysql'] = $app->share(function($app) {
	$connParams = $app['db.config.mysql'];
	return $app['db.mysql.getConnection']($connParams);
});

$app['db.mongodb'] = $app->share(function($app) {
	$connParams = $app['db.config.mongodb'];
	$conn = $app['db.mongodb.getConnection']($connParams);
	$db = $conn->selectDatabase($connParams['database']);
	return $db;
});

$app['db.mysql.collection'] = function($app) {
	return new LogMon\Databases\MysqlDBCollection($app['db.mysql']);
};

$app['db.mongodb.collection'] = function($app) {
	return new LogMon\Databases\MongoDBCollection($app['db.mongodb']);
};


$app['projects'] = $app->share(function($app) {
	return new LogMon\Projects\Manager(
		$app,  // required when the manager builds logConfig objects.
		$app['db.mongodb.collection']
	);
});

$app['project.factory'] = function($app) {
	return new LogMon\Projects\Project();
};

require ROOT.'src/LogMon/router.php';

return $app;
