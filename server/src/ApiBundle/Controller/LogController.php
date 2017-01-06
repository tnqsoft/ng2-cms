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
class LogController extends BaseRestController
{
    /**
     * Write Log
     *
     * @Post("/add")
     * @View(statusCode=204)
     * @ApiDoc(
     *  description="Write Log",
     *  section="Logger",
     *  parameters={
     *      {"name"="code", "dataType"="string", "required"=true, "description"="Exception code"},
     *      {"name"="message", "dataType"="string", "required"=true, "description"="Exception message"},
     *      {"name"="context", "dataType"="array", "required"=true, "description"="Exception context"}
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
        $arguments = $this->getValidator($request, 'addLogValidate');
        $logger = $this->get('app.service.client_logger');
        $logger->log($arguments['code'], $arguments['message'], $arguments['context']);
    }

    public function tranformRecord($record)
    {
        // Nothing Todo
    }

    /**
     * Get and Validate Input Data.
     *
     * @param Request $request
     * @param string $functionName
     * @param User $currentObject
     * @return array
     */
    public function getValidator(Request $request, $functionName, $currentObject=null)
    {
        //Get Input Data
        $arguments = json_decode($request->getContent(), true);
        if (null === $arguments) {
            $arguments = array();
        }

        //Validate Input Data
        $validator = $this->get('app.validator.log');

        if (false === call_user_func_array(array($validator, $functionName), array($arguments))) {
            throw new HttpException(400, json_encode($validator->getErrorList()));
        }

        $arguments['context'] = $this->getArrayValue('context', $arguments, array());

        return $arguments;
    }
}
