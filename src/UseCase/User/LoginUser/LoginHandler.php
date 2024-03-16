<?php

declare(strict_types=1);

namespace App\UseCase\User\LoginUser;

use App\AppBundle\Service\TokenGeneratorService;
use App\DTO\ResponseDto\LoginResultDto;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Exception as DbalException;
use Exception;

readonly class LoginHandler
{
    public function __construct(
        public TokenGeneratorService $service,
        public LoginManager $manager,
    ) {
    }

    /**
     * @throws InvalidCredentialsException
     * @throws DbalException
     * @throws Exception
     */
    public function handle(?User $user): LoginResultDto
    {
        if (null === $user) {
            throw new InvalidCredentialsException('Invalid credentials');
        }

        $activeUserTokenId = $this->manager->getActiveUserTokenId($user->getId());

        if ($activeUserTokenId) {
            $this->manager->deactivateUserTokenId(
                ['active' => 0, 'closed_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s')],
                ['id' => $activeUserTokenId]
            );
        }

        $token = $this->service->generateToken();
        $this->manager->saveUserToken([
            'active' => 1,
            'token' => $token,
            'started_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            'user_id' => $user->getId(),
        ]);

        return new LoginResultDto($token);
    }
}
