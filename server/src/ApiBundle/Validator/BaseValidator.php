<?php

namespace ApiBundle\Validator;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Collection;

class BaseValidator
{
    /**
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $errorList;

    /**
     * @var boolean
     */
    protected $allowExtraFields = true;

    /**
     * @var string
     */
    protected $extraFieldsMessage = '';

    /**
     * @var string
     */
    protected $missingFieldsMessage = '';

    /**
     * @var array
     */
    protected $dataInput;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->errorList = array();
    }

    /**
    * Set Validator
    *
    * @param RecursiveValidator $validator
    */
    public function setValidator(RecursiveValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get error list
     *
     * @return array
     */
    public function getErrorList()
    {
        return $this->errorList;
    }

    /**
     * Validate
     *
     * @param array $input
     * @return boolean
     */
    public function validate($input)
    {
        $this->dataInput = $input;

        $this->errorList = array();

        //Create constraint
        $constraintCollection = array(
            'fields' => $this->collections,
            'allowExtraFields' => $this->allowExtraFields,
        );
        if($this->extraFieldsMessage !== '') {
            $constraintCollection['extraFieldsMessage'] = $this->extraFieldsMessage;
        }
        if($this->missingFieldsMessage !== '') {
            $constraintCollection['missingFieldsMessage'] = $this->missingFieldsMessage;
        }
        $constraint = new Collection($constraintCollection);

        //Validate
        $validatorResult = $this->validator->validate($input, $constraint);

        //Parse error
        $this->parseError($validatorResult);

        return (count($this->errorList) === 0);
    }

    /**
     * Parse error
     *
     * @param  ConstraintViolationList $validatorResult
     * @return void
     */
    private function parseError(ConstraintViolationList $validatorResult)
    {
        $this->errorList = array();
        foreach ($validatorResult as $error) {
            $path = $error->getPropertyPath();
            $path = preg_replace("/\]\[/", "/", $path);
            $path = preg_replace("/\[|\]/", "", $path);
            $value = $error->getMessage();
            $tokens = explode('/', $path);
            while (null !== ($key = array_pop($tokens))) {
                $current = array($key => $value);
                $value = $current;
            }
            $this->errorList = array_replace_recursive($this->errorList, $value);
        }
    }
}
