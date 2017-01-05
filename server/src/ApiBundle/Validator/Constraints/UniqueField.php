<?php

namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueField extends Constraint
{
    public $entityClass;
    public $field;
    public $id;
    public $message = 'Đã tồn tại "{{ value }}" của trường {{ field }} trong bảng';
}
