<?php
namespace Logmon\Auth;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthFailureHandler implements AuthenticationFailureHandlerInterface
{
	/**
	 * This is called when an interactive authentication attempt fails. This is
	 * called by authentication listeners inheriting from
	 * AbstractAuthenticationListener.
	 *
	 * @param Request				 $request
	 * @param AuthenticationException $exception
	 *
	 * @return Response The response to return, never null
	 */
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		return new Response('Login failed.', 500);
	}
}
