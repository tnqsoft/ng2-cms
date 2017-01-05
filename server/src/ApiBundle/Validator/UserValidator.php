<?php

namespace ApiBundle\Validator;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ExecutionContext;
use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\User;

class UserValidator extends BaseValidator
{
    /**
     * EntityManager.
     */
    private $entityManager;

    /**
     * Set EntityManager.
     *
     * @param UserRepository $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Change Password Validate.
     *
     * @return array
     */
    public function changePasswordValidate($input)
    {
        $this->collections = array(
            'oldPassword' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new SecurityAssert\UserPassword(array('message' => 'Nhập sai mật khẩu cũ')),
            )),
            'newPassword' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Length(array('min' => 6, 'minMessage' => 'Mật khẩu tối thiếu có 6 ký tự')),
            )),
        );

        return $this->validate($input);
    }

    /**
     * Update User Validate.
     *
     * @return array
     */
    public function addAndUpdateValidate($input)
    {
        $collections = array(
            'username' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Callback(array('methods' => array(
                    array($this, 'checkUsernameExisted'),
                ))),
            )),
            'password' => new Constraints\Optional(array(
                new Constraints\Length(array('min' => 6, 'minMessage' => 'Mật khẩu tối thiếu có 6 ký tự')),
            )),
            'email' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Email(array('message' => 'Sai định dạng Email')),
            )),
            'isActive' => new Constraints\Required(array(
                //new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Type(array('type' => 'boolean', 'message' => 'Phải là kiểu boolean [True|False]')),
            )),
        );

        $this->collections = $collections;

        return $this->validate($input);
    }

    /**
     * Checks that username is already existed.
     *
     * @param string           $username
     * @param ExecutionContext $context
     */
    public function checkUsernameExisted($username, ExecutionContext $context)
    {
        $repository = $this->entityManager->getRepository(User::class);
        $record = $repository->findOneBy(array('username' => $username));
        if ($record instanceof User) {
            $context->addViolation('Username "'.$username.'" đã tồn tại.', array(), null);
        }
    }
}
