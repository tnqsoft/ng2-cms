<?php

namespace ApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DuplicatedValue extends Constraint
{
    public $message = 'Bị trùng giá trị "{{ value }}" trong mảng {{ array }}';
}
