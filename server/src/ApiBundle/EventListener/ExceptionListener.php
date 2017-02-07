<?php
namespace ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

use ApiBundle\Exception\Constant\Type;
use ApiBundle\Exception\AppException;

class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $code = ($exception instanceof HttpException)?$exception->getStatusCode():500;
        $message = $exception->getMessage();

        if ($exception instanceof AppException) {
            $responseData = $exception->__toArray();
        } else {
            $responseData = array(
                'statusCode' => $code,
                "type" => Type::COMMON_ERROR,
                'message' => $message,
            );
        }

        $response = new JsonResponse($responseData, $code);

        $event->setResponse($response);
    }
}
