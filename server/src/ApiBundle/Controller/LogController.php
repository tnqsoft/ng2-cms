<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * LogController
 */
class LogController extends FOSRestController
{
    /**
     * Write Log
     *
     * @Post("/add")
     *
     * @ApiDoc(
     *  description="Write Log",
     *  section="Logger",
     *  parameters={
     *      {"name"="code", "dataType"="string", "required"=true, "description"="Exception code"},
     *      {"name"="message", "dataType"="string", "required"=true, "description"="Exception message"},
     *      {"name"="context", "dataType"="string", "required"=true, "description"="Exception context"}
     *  },
     *  statusCodes={
     *    200="Returned when successful",
     *    401="Returned when not have token or token expired",
     *    400="Returned if not validated",
     *  }
     * )
     *
     * @return array
     */
    public function writeLogAction(Request $request)
    {
        $arguments = json_decode($request->getContent(), true);
        if (null === $arguments) {
            $arguments = array();
        }

        $logger = $this->get('app.service.client_logger');
        $logger->log($arguments['code'], $arguments['message'], json_decode($arguments['context'], true));

        return null;
    }
}
