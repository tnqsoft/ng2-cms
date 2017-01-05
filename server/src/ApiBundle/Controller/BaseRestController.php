<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
 * BaseRestController.
 */
abstract class BaseRestController extends FOSRestController
{
    /**
     * Tranform Record.
     *
     * @param mixed $record
     *
     * @return array
     */
    abstract public function tranformRecord($record);

    /**
     * Get and Validate Input Data.
     *
     * @param Request $request
     * @return array
     */
    abstract public function getAndValidateInputData(Request $request);

    /**
     * Get Response For List
     *
     * @return Response
     */
    public function getResponseForList($paginator)
    {
        $list = array();
        foreach ($paginator as $record) {
            $list[] = $this->tranformRecord($record);
        }

        $response = new Response(json_encode($list), Response::HTTP_OK);
        $response->headers->set('Data-Total-Record', $paginator->getTotalRecord());
        $response->headers->set('Data-Total-Page', $paginator->getTotalPage());
        $response->headers->set('Data-Page-Current', $paginator->getPage());
        $response->headers->set('Data-Page-Previous', $paginator->getPrevPage());
        $response->headers->set('Data-Page-Next', $paginator->getNextPage());
        $response->headers->set('Data-Limit', $paginator->getLimit());
        $response->headers->set('Data-Record-Start', $paginator->getStartRecord());
        $response->headers->set('Data-Record-End', $paginator->getEndRecord());

        return $response;
    }

    /**
     * Get Repository.
     *
     * @return mixed
     */
    public function getRepository($class)
    {
        $em = $this->getDoctrine()->getManager();        

        return $em->getRepository($class);
    }

    /**
     * Get Record By Id.
     *
     * @param string $class
     * @param int $id
     *
     * @return mixed
     */
    public function getRecordById($class, $id)
    {
        $repository = $this->getRepository($class);        
        $record = $repository->findOneById($id);
        if (null === $record) {
            throw new HttpException(404, 'Không tìm thấy bản ghi '.$class.' có id là '.$id);
        }

        return $record;
    }
}
