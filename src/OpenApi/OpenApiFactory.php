<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

class OpenApiFactory implements OpenApiFactoryInterface {

	public function __construct( private readonly OpenApiFactoryInterface $decorated ) {
	}

	/**
	 * @inheritDoc
	 */
	public function __invoke( array $context = [] ): OpenApi {
		$openApi = $this->decorated->__invoke( $context );

		$paths = $openApi->getPaths()->getPaths();

		/**
		 * @var Model\PathItem $pathItem
		 */
		foreach ( $paths as $path => $pathItem ) {
			if ( $pathItem->getGet() && $pathItem->getGet()->getSummary() === 'hidden' ) {
				$openApi->getPaths()->addPath( $path, $pathItem->withGet( null ) );
			}
		}

		$securitySchemes = $openApi->getComponents()->getSecuritySchemes();
		$schemes         = $openApi->getComponents()->getSchemas();

		$securitySchemes['cookieAuth'] = new \ArrayObject( [
			'type' => 'apiKey',
			'in'   => 'cookie',
			'name' => 'PHPSESSID'
		] );

		$schemes['Credentials'] = new \ArrayObject( [
			'type'       => 'object',
			'properties' => [
				'username' => [
					'type'    => 'string',
					'example' => 'robert.alien@gmail.com'
				],
				"password" => [
					'type'    => 'string',
					'example' => '0000'
				]
			]
		] );

		$loginPathItem = new Model\PathItem(
			post: new Model\Operation(
				operationId: 'postApiLogin',
				tags: [ 'Auth' ],
				responses: [
					'200' => [
						'description' => 'Connexion utilisateur',
						'content'     => [
							'application/json' => [
								'schema' => [ '$ref' => '#/components/schemas/User-read.User' ]
							]
						]
					]

				],
				requestBody: new Model\RequestBody(
					content: new \ArrayObject( [
						'application/json' => [
							'schema' => [
								'$ref' => '#/components/schemas/Credentials'
							],
						]
					] )
				)
			)
		);

		$openApi->getPaths()->addPath( '/api/login ', $loginPathItem );


		$logoutPathItem = new Model\PathItem(
			post: new Model\Operation(
				operationId: 'postApiLogout',
				tags: [ 'Auth' ],
				responses: [
					'204' => []
				]
			)
		);

		$openApi->getPaths()->addPath( '/api/logout ', $logoutPathItem );

		/**
		 * Supprime les paramètres automatiques dans le chemin /api/me
		 */
		$meOperation = $openApi->getPaths()->getPath( '/api/me' )->getGet()->withParameters( [] );
		$mePathItem  = $openApi->getPaths()->getPath( '/api/me' )->withGet( $meOperation );
		$openApi->getPaths()->addPath( '/api/me', $mePathItem );

		return $openApi;
	}
}
