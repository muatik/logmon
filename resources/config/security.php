<?php
use LogMon\Auth\AuthFailureHandler;
use LogMon\Auth\AuthSuccessHandler;

/**
 * AUTHENTICATION CONFIGURATIONS
 * ------------------------------------------------------------------
 */

$app['security.authentication.success_handler.default'] = $app->share(function ($app) {
	return new AuthSuccessHandler($app['db.mongodb'], $app['session']);
});

$app['security.authentication.failure_handler.default'] = $app->share(function ($app) {
	return new AuthFailureHandler();
});

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'authLayer' => array(
			'pattern' => '/API/v1/auth/registration',
			'security' => false
		),
		'default' => array(
			'pattern' => '^/API/v1/.*',
			'anonymous' => false,
			'form' => array(
				'login_path' => '/API/v1/auth/failure', 
				'check_path' => '/API/v1/auth',
				'username_parameter' => 'email',
				'password_parameter' => 'password',
				'use_forward' => true,
				'require_previous_session' => false
			),
			'logout' => array(
				'logout_path' => '/API/v1/auth/logout'
			),
			'users' => function () use($app) {
				return new Logmon\Auth\UserProvider($app['db.mongodb']);
			}
		) // end of default firewall
	),
	'security.access_rules' => array(
		array('^/API/v1/.*', 'ROLE_USER')
	)
));

$app['user'] = $app->share(function($app) {	
	$token = $app['security']->getToken();
    if ($token && !$app['security.trust_resolver']->isAnonymous($token)) {		
        return $token->getUser();
	}
	return null;
});
