<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use ApiBundle\Exception\Entity\Problem;
use JMS\Serializer\SerializerBuilder;

class AppException extends HttpException
{
    /**
     * @var Problem
     */
    private $problem;

    /**
     * __construct
     * @param Problem $problem
     * @param \Exception  $previous
     */
    public function __construct(Problem $problem, \Exception $previous = null)
    {
        $this->problem = $problem;
        $statusCode = $problem->getStatusCode();
        $message = $problem->getMessage();
        $headers = $problem->getHeaders();
        $code = $problem->getCode();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * __toArray
     *
     * @return array
     */
    public function __toArray()
    {
        $serializer = SerializerBuilder::create()->build();
        return json_decode($serializer->serialize($this->problem, 'json'), true);
    }

    /**
     * Get the value of Problem
     *
     * @return Problem
     */
    public function getProblem()
    {
        return $this->problem;
    }

}
