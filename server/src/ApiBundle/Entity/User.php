<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("all")
 *
 * Defines the properties of the User entity to represent the application users.
 * See http://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See http://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 *
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @var string
     */
    private $newPassword;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Expose
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Expose
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(name="reset_token", type="string", nullable=true)
     * @Expose
     */
    private $resetToken;

    /**
     * @ORM\Column(name="reset_timeout", type="datetime", nullable=true)
     * @Expose
     */
    private $resetTimeout;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Expose
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Expose
     */
    private $updatedAt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     * @Expose
     */
    private $roles;

    public function __construct()
    {
        $this->isActive = true;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        return $this->roles = $roles;
    }

    public function addRoles($role)
    {
        if(!$this->hasRole($role)) {
            $this->roles[] = $role;
        }
    }

    public function removeRoles($role)
    {
        if($this->hasRole($role)) {
            array_splice($this->roles, array_search($role, $this->roles), 1);
        }
    }

    public function hasRole($role) {
       $role = strtoupper($role);
       $roles = $this->getRoles();
       return in_array($role, $roles, true);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt()
    {
        // See "Do you need to use a Salt?" at http://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    // serialize and unserialize must be updated - see below
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ) = unserialize($serialized);
    }

    /**
     * Get the value of Is Active
     *
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set the value of Is Active
     *
     * @param mixed isActive
     *
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * @param mixed $resetToken
     * @return User
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResetTimeout()
    {
        return $this->resetTimeout;
    }

    /**
     * @param DateTime $resetTimeout
     * @return User
     */
    public function setResetTimeout(\DateTime $resetTimeout)
    {
        $this->resetTimeout = $resetTimeout;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     * @return self
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate
     * @return self
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of New Password
     *
     * @param string newPassword
     *
     * @return self
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Get the value of New Password
     *
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }
}
