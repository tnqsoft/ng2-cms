<?php

namespace ApiBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Exception\Constant\Type;
use ApiBundle\Exception\Entity\Problem;
use ApiBundle\Exception\Entity\Error;
use ApiBundle\Exception\AppException;

class ApiAuthenticator implements SimpleFormAuthenticatorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(UserPasswordEncoderInterface $encoder, TranslatorInterface $translator)
    {
        $this->encoder = $encoder;
        $this->translator = $translator;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            // throw new CustomUserMessageAuthenticationException('Invalid username or password');
            $problem = new Problem();
            $problem->setStatusCode(Response::HTTP_UNAUTHORIZED);
            $problem->setMessage($this->translator->trans(Type::AUTHENTICATION_ERROR, array(), 'messages'));
            $problem->setType(Type::AUTHENTICATION_ERROR);
            $error = new Error();
            $error->setItem('username');
            $error->setMessage($this->translator->trans('Account does not existed', array(), 'messages'));
            $problem->addError($error);
            throw new AppException($problem);
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());

        if (false === $passwordValid) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            // throw new CustomUserMessageAuthenticationException('Invalid username or password');
            $problem = new Problem();
            $problem->setStatusCode(Response::HTTP_UNAUTHORIZED);
            $problem->setMessage($this->translator->trans(Type::AUTHENTICATION_ERROR, array(), 'messages'));
            $problem->setType(Type::AUTHENTICATION_ERROR);
            $error = new Error();
            $error->setItem('password');
            $error->setMessage($this->translator->trans('Password incorrect', array(), 'messages'));
            $problem->addError($error);
            throw new AppException($problem);
        }

        if (false === $user->isEnabled()) {
            $problem = new Problem();
            $problem->setStatusCode(Response::HTTP_UNAUTHORIZED);
            $problem->setMessage($this->translator->trans(Type::AUTHENTICATION_ERROR, array(), 'messages'));
            $problem->setType(Type::AUTHENTICATION_ERROR);
            $error = new Error();
            $error->setItem('username');
            $error->setMessage($this->translator->trans('Account is locked', array(), 'messages'));
            $problem->addError($error);
            throw new AppException($problem);
        }

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        if (trim($username) === '' && trim($password) === '') {
            $problem = new Problem();
            $problem->setStatusCode(Response::HTTP_BAD_REQUEST);
            $problem->setMessage($this->translator->trans(Type::VALIDATION_ERROR, array(), 'messages'));
            $problem->setType(Type::VALIDATION_ERROR);
            $error = new Error();
            $error->setItem('username');
            $error->setMessage($this->translator->trans('Field is mandatory', array(), 'messages'));
            $problem->addError($error);
            $error = new Error();
            $error->setItem('password');
            $error->setMessage($this->translator->trans('Field is mandatory', array(), 'messages'));
            $problem->addError($error);
            throw new AppException($problem);
        }
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
