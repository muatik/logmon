<?php

$app['locale'] = 'en';
$app['resources_path'] = realpath(__DIR__.'/../resources/');

/**
 * DATA STORAGE CONFIGURATIONS
 * ------------------------------------------------------------------
 * This section sets where the application will save its data such as projects,
 * users' configurations, alarms etc.
 */

// If you use MySQL, configure and comment out the following set:
$app['db.config.mysql'] = array(
	'host' => 'localhost',
	'database' => 'logmon',
	'user' => '',
	'password' => ''
);

// If you use MongoDB, configure and comment out the following set:
$app['db.config.mongodb'] = array(
	'host' => 'mongodb://localhost/',
	'database' => 'logmon',
	'auth' => false, // Does mongodb require authentication?
	'user' => '',
	'password' => ''
);

// If you use a plain text file, configure and comment out the following set:
$app['db.config.file'] = array(
	'path' => ROOT.'/resources/storage/'
);



/**
 * LOGGING CONFIGURATIONS
 * ------------------------------------------------------------------
 * Warning: This is about the applications itself loging, not projects.
 */

// if you would like to save logs in a text file, use the following set:
$app['logging.target'] = 'file';
$app['logging.file.config'] = $app['db.config.file'];
$app['logging.file.name'] = date('Y:m').'.log';


// if you would like to save logs in MongoDB, use the following set: 
$app['logging.target'] = 'mongodb';
$app['logging.mongodb.config'] = $app['db.config.mongodb'];
// the database is optional. if specified, overrides db.config.mongodb.database
$app['logging.database'] = 'logmong'; 
$app['logging.collection'] = 'logs';


// if you would like to save logs in MySQL, use the following set: 
$app['logging.target'] = 'mysql';
$app['logging.mysql.config'] = $app['db.config.mysql'];
// the database is optional. if specified, overrides db.config.mysql.database
$app['logging.database'] = 'logmong'; 
$app['logging.table'] = 'logs';









$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
	'http_cache.cache_dir' => ROOT.'/temp/http'
));

$app['db.mongo'] = $app->share(
	function($app) {
		return new Mongo($app['db.config.mongodb']);
	}
);

?>
