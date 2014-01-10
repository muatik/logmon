<?php
namespace LogMon\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use LogMon\Auth\UserProvider;
use Symfony\Component\HttpFoundation\Response;

class Auth implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$router = $app['controllers_factory'];

		$router->get('/failure', array($this, 'failure'));
		$router->post('/signup', array($this, 'register'));

		$this->userProvider = new UserProvider($app['db.mongodb']);
		return $router;
	}

	public function failure(Application $app) 
	{
		return new Response('Login required.', 500);
		return $app->json($response);
	}

	public function register(Application $app) 
	{
		$response = new Response();
		$email = $app['request']->get('email');
		$password = $app['request']->get('password');
		try {
			$this->userProvider->register($email,$password);
			return new Response(sprintf(
				"The email '%s' has just been registered.", $email), 200);
			$response->setStatusMessage();
		} catch (\Exception $e) {
			return new Response($e->getMessage(), 500);
		}
	}
}
