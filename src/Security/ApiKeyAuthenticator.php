<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator {

	public function supports( Request $request ): ?bool {
		$auth = $request->headers->get( 'authorization' );
		return $auth && str_starts_with( $auth, 'Bearer' );
	}

	public function authenticate( Request $request ): Passport {
		$apiToken = str_replace( 'Bearer ', '', $request->headers->get( 'authorization' ) );
		if ( null === $apiToken ) {
			// The token header was empty, authentication fails with HTTP Status
			// Code 401 "Unauthorized"
			throw new CustomUserMessageAuthenticationException( new JsonResponse( [ 'error' => 'Auth header require' ] ) );
		}

		return new SelfValidatingPassport( new UserBadge( $apiToken ));
	}

	public function onAuthenticationSuccess( Request $request, TokenInterface $token, string $firewallName ): ?Response {
		return null;
	}

	public function onAuthenticationFailure( Request $request, AuthenticationException $exception ): ?Response {
		$data = [
			// you may want to customize or obfuscate the message first
			'message' => strtr( $exception->getMessageKey(), $exception->getMessageData() )

			// or to translate this message
			// $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
		];

		return new JsonResponse( $data, Response::HTTP_FORBIDDEN );
	}
}
