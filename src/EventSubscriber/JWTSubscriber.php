<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTSubscriber implements EventSubscriberInterface {
	/**
	 * @param $event JWTCreatedEvent
	 *
	 * @return void
	 */
	public function onLexikJwtAuthenticationOnJwtCreated( JWTCreatedEvent $event ): void {
		$payload          = $event->getData();
		$payload['username'] = $event->getUser()->getUserIdentifier();
		$event->setData( $payload );
	}

	public static function getSubscribedEvents(): array {
		return [
			'lexik_jwt_authentication.on_jwt_created' => 'onLexikJwtAuthenticationOnJwtCreated',
		];
	}
}
