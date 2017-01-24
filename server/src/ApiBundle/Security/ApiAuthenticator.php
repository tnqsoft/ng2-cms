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
            throw new HttpException(401, $this->translator->trans('Account does not existed', array(), 'messages'));
        }

        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());

        if (false === $passwordValid) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            // throw new CustomUserMessageAuthenticationException('Invalid username or password');
            throw new HttpException(401, $this->translator->trans('Password incorrect', array(), 'messages'));
        }

        if (false === $user->isEnabled()) {
            throw new HttpException(401, $this->translator->trans('Account is locked', array(), 'messages'));
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
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
