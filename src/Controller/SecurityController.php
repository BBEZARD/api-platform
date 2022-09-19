<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController {

	#[Route(
		'/api/login',
		name: 'api_login',
		methods: [ 'post' ]
	)]
	public function login(): Response {
		$user = $this->getUser();

		return $this->json( [
			'username' => $user->getUserIdentifier(),
			'roles'    => $user->getRoles()
		] );
	}

	#[Route(
		'/api/logout',
		name: 'api_logout',
		methods: [ 'post' ]
	)]
	public function logout() {


	}

}
