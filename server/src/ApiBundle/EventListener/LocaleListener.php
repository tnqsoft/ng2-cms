<?php

namespace ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * __construct
     *
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * onKernelRequest
     *
     * @param  GetResponseEvent $event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (null !== $request->attributes->get('_locale')) {
            $locale = $request->attributes->get('_locale');
        } elseif (null !== $request->headers->get('Accept-Language')) {
            $locale = $request->headers->get('Accept-Language');
        } else {
            $locale = $this->defaultLocale;
        }

        $request->setLocale($locale);
    }

    /**
     * Get Subscribed Events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}
