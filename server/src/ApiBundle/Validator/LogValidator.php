<?php

namespace ApiBundle\Validator;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use ApiBundle\Validator\Constraints\DuplicatedValue;

class LogValidator extends BaseValidator
{
    /**
     * Add Log Validate.
     *
     * @return array
     */
    public function addLogValidate($input)
    {
        $this->collections = array(
            'code' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
            )),
            'message' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Length(array('max' => 255, 'maxMessage' => 'Mật khẩu tối đa có 255 ký tự')),
            )),
            'context' => new Constraints\Optional(array(
                new Constraints\Type(array('type' => 'array', 'message' => 'Phải là kiểu mảng giá trị')),
                new DuplicatedValue(),
                new Constraints\All(array(
                    new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                    new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                )),
            )),
        );

        return $this->validate($input);
    }
}
