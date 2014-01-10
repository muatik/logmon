<?php
namespace LogMon\Auth;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LogMon\Auth\UserProvider;

class AuthSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	public function __construct(\Doctrine\MongoDB\Database $db , $sessionManager)
	{
		$this->userProvider = new  UserProvider($db);
		$this->sessionManager = $sessionManager;
	}
	
	/**
	 * This is called when an interactive authentication attempt succeeds. This
	 * is called by authentication listeners inheriting from
	 * AbstractAuthenticationListener.
	 *
	 * @param Request		$request
	 * @param TokenInterface $token
	 *
	 * @return Response never null
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		$this->userProvider->updateLastLoginAt($request->get('email'));	
		$response =new \LogMon\Helpers\Response;
		$response->setStatusMessage('Login was successful.');
		$response->setData('CREDENTIAL_ID',$this->sessionManager->getId());		
		return new Response(json_encode($response), 200);
	}
}
