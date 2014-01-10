<?php
namespace LogMon\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use LogMon\Auth\UserProvider;
use LogMon\Helpers\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Auth implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$router = $app['controllers_factory'];

		$router->get('/failure', array($this, 'failure'));
		$router->post('/registration', array($this, 'register'));

		$this->userProvider = new UserProvider($app['db.mongodb']);
		return $router;
	}

	public function failure(Application $app) 
	{
		$response = new Response();
		$response->setStatus(30, 'Login required!!!');
		return $app->json($response);
	}

	public function register(Application $app) 
	{
		$response = new Response();
		$email = $app['request']->get('email');
		$password = $app['request']->get('password');
		try {
			$this->userProvider->register($email,$password);
			
			$response->setStatusMessage(sprintf(
				"The email '%s' has just been registered.", $email));
			$params = array('email' => $email,'password' => $password);
				
			$subRequest = Request::create('/API/v1/auth', 'POST', $params);
			$app->handle($subRequest, HttpKernelInterface::MASTER_REQUEST);
						
			$response->setData('CREDENTIAL_ID',$app['session']->getId());
			
		} catch (\Exception $e) {
			$response->setStatus(30, $e->getMessage());
		}

		return $app->json($response);
	}
}
