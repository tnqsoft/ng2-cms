<?php

namespace ApiBundle\Exception\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\SerializerBuilder;

use ApiBundle\Exception\Entity\Error;

/**
 * @ExclusionPolicy("all")
 */
class Problem
{
    /**
     * @var string
     * @Expose
     * @SerializedName("statusCode")
     */
    private $statusCode;

    /**
     * @var string
     * @Expose
     */
    private $type;

    /**
     * @var string
     * @Expose
     */
    private $message;

    /**
     * @var array
     * @Expose
     */
    private $errors;

    /**
     * @var string
     */
    private $code;

    /**
     * @var array
     */
    private $headers;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->errors = array();
        $this->headers = array();
        $this->code = 0;
    }

    /**
     * Get the value of Status Code
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the value of Status Code
     *
     * @param string statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the value of Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of Type
     *
     * @param string type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of Message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of Message
     *
     * @param string message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of Errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set the value of Errors
     *
     * @param array errors
     *
     * @return self
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Add a error
     *
     * @param Error error
     *
     * @return self
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Get the value of Code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of Code
     *
     * @param string code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of Headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the value of Headers
     *
     * @param array headers
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

}
