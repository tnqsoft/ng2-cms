<?php

namespace ApiBundle\Validator;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\User;
use ApiBundle\Validator\Constraints\DuplicatedValue;

class UserValidator extends BaseValidator
{
    /**
     * @var User
     */
    private $currentObject = null;

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
     * @param  array $input
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
     * @param  array $input
     * @return array
     */
    public function addAndUpdateValidate($input)
    {
        $collections = array(
            'username' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Callback(array($this, 'checkUsernameExisted')),
            )),
            'email' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Email(array('message' => 'Sai định dạng Email')),
                new Constraints\Callback(array($this, 'checkEmailExisted')),
            )),
            'isActive' => new Constraints\Required(array(
                //new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Type(array('type' => 'boolean', 'message' => 'Phải là kiểu boolean [True|False]')),
            )),
            'roles' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Type(array('type' => 'array', 'message' => 'Phải là kiểu mảng giá trị')),
                new DuplicatedValue(),
                new Constraints\All(array(
                    new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                    new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                    new Constraints\Length(array('max' => 255, 'maxMessage' => 'Tối đa nhập 255 ký tự.')),
                )),
            )),
        );

        if (null !== $this->currentObject->getId() && 0 !== $this->currentObject->getId()) {
            $collections['password'] = new Constraints\Optional(array(
                new Constraints\Length(array('min' => 6, 'minMessage' => 'Mật khẩu tối thiếu có 6 ký tự')),
            ));
        } else {
            $collections['password'] = new Constraints\Required(array(
                new Constraints\Length(array('min' => 6, 'minMessage' => 'Mật khẩu tối thiếu có 6 ký tự')),
            ));
        }

        $this->collections = $collections;

        return $this->validate($input);
    }

    /**
     * Update Profile Validate
     *
     * @param  array $input
     * @return array
     */
    public function updateProfileValidate($input)
    {
        $this->collections = array(
            'email' => new Constraints\Required(array(
                new Constraints\NotBlank(array('message' => 'Không được để rỗng')),
                new Constraints\NotNull(array('message' => 'Không được để rỗng')),
                new Constraints\Email(array('message' => 'Sai định dạng Email')),
                new Constraints\Callback(array($this, 'checkEmailExisted')),
            )),
        );

        return $this->validate($input);
    }

    /**
     * Set Current Object
     *
     * @param User $currentObject
     */
    public function setCurrentObject(User $currentObject)
    {
        $this->currentObject = $currentObject;
    }

    /**
     * Checks that username is already existed.
     *
     * @param string           $username
     * @param ExecutionContext $context
     */
    public function checkUsernameExisted($username, ExecutionContextInterface $context, $payload)
    {
        $repository = $this->entityManager->getRepository(User::class);
        $record = $repository->findOneBy(array('username' => $username));
        if ($record instanceof User && ($record->getId() !== $this->currentObject->getId() && $record->getUsername() === $username)) {
            $context->addViolation('Username "'.$username.'" đã tồn tại.', array(), null);
        }
    }

    /**
     * Checks that email is already existed.
     *
     * @param string           $email
     * @param ExecutionContext $context
     */
    public function checkEmailExisted($email, ExecutionContextInterface $context, $payload)
    {
        $repository = $this->entityManager->getRepository(User::class);
        $record = $repository->findOneBy(array('email' => $email));
        if ($record instanceof User && ($record->getId() !== $this->currentObject->getId() && $record->getEmail() === $email)) {
            $context->addViolation('Email "'.$email.'" đã tồn tại.', array(), null);
        }
    }
}
