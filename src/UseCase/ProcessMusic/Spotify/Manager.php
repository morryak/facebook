<?php

declare(strict_types=1);

namespace App\UseCase\ProcessMusic\Spotify;

use App\UseCase\AbstractManager;
use DateTimeImmutable;
use Doctrine\DBAL\Exception as DbalException;

class Manager extends AbstractManager
{
    /**
     * @throws DbalException
     */
    public function getToken(): string
    {
        $sql = 'SELECT token FROM token';

        return $this->getConnection()->fetchOne($sql);
    }

    /**
     * @throws DbalException
     */
    public function updateToken(string $access_token): void
    {
        $this->getConnection()->update(
            'token',
            [
                'token' => $access_token,
                'updated_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            ['id' => 1]
        );
    }
}
