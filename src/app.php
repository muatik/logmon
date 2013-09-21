<?php

require ROOT."/vendor/autoload.php";

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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

$app['db.mongo'] = $app->share(
	function($app) {
		return new Mongo($app['db.config.mongodb']);
	}
);


require ROOT.'src/router.php';


return $app;

?>
