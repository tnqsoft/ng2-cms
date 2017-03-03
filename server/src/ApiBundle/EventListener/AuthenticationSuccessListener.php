<?php

namespace ApiBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
	/**
	 * @param AuthenticationSuccessEvent $event
	 */
	public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
	{
	    $data = $event->getData();
	    $user = $event->getUser();

	    if (!$user instanceof UserInterface) {
		return;
	    }

	    $data['data'] = array(
		'test' => 'xxxxxx',
		'roles' => $user->getRoles()
	    );

	    $event->setData($data);
	}
}
