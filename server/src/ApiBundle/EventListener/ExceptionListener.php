<?php
namespace ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $code = 500;
        $type = '';

        $message = $exception->getMessage();
        if (json_decode($message) !== null) {
            $message = json_decode($message);
        }

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
        }

        $responseData = array(
            'code' => $code,
            'error' => $message
        );

        $event->setResponse(new JsonResponse($responseData, $code));
    }
}
