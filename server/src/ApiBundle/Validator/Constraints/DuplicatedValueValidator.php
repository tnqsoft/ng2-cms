<?php

namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DuplicatedValueValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if(!is_array($value)) {
            return true;
        }

        $isDuplicatedValue = false;
        $duplicatedValue = null;
        for($i=0;$i<count($value)-1;$i++) {
            for($j=$i+1;$j<count($value);$j++) {
                if($value[$i] == $value[$j]) {
                    $isDuplicatedValue = true;
                    $duplicatedValue = $value[$j];
                }
            }
        }
        if($isDuplicatedValue) {
            $this->context->addViolation(
                    $constraint->message,
                    array(
                        '{{ value }}' => $duplicatedValue,
                        '{{ array }}' => json_encode($value),
                    )
                );
        }
    }
}
