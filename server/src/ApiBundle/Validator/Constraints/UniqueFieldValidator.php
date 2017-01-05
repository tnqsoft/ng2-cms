<?php

namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class UniqueFieldValidator extends ConstraintValidator
{
    /**
     * EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $record = $this->entityManager->getRepository($constraint->entityClass)
                    ->findOneBy(array($constraint->field => $value));

        if (null !== $record && $record->getId() != $constraint->id) {
            $this->context->addViolation(
                    $constraint->message,
                    array(
                        '{{ value }}' => $value,
                        '{{ field }}' => $constraint->field,
                    )
                );
        }
    }
}
