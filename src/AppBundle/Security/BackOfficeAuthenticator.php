<?php

declare(strict_types=1);

namespace App\AppBundle\Security;

use MarfaTech\Bundle\ApiPlatformBundle\Exception\ApiException;
use MarfaTech\Bundle\DbalBundle\Exception\SelectDbalException;
use MarfaTech\Bundle\DbalBundle\Exception\WriteDbalException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class BackOfficeAuthenticator extends П
{
    private int $tokenExpireTime;

    /**
     * Minutes in one year.
     */
    public const YEAR_TIMEOUT_SESSION = 525960;

    public function __construct(int $tokenExpireTime)
    {
        $this->tokenExpireTime = $tokenExpireTime;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new ApiException(ApiException::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('X-Auth-Token');
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request): string
    {
        return $request->headers->get('X-Auth-Token') ?: '';
    }

    /**
     * {@inheritdoc}
     *
     * @throws WriteDbalException
     * @throws SelectDbalException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ($credentials === null) {
            return null;
        }

        /** @var BackOfficeAdminProvider $userProvider */
        $backOfficeAdminDto = $userProvider->findBackOfficeAdminByActiveToken($credentials);

        if ($backOfficeAdminDto === null) {
            return null;
        }

        if ($backOfficeAdminDto->isFinancier()) {
            $userProvider->updateTokenExpirationDate($credentials, $this->tokenExpireTime);

            return $backOfficeAdminDto;
        }

        $userProvider->updateTokenExpirationDate($credentials, self::YEAR_TIMEOUT_SESSION);

        return $backOfficeAdminDto;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new ApiException(ApiException::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
