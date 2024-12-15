<?php

declare(strict_types=1);

namespace App\Manager;

use App\UseCase\AbstractManager;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;

class VkServiceManager extends AbstractManager
{
    /**
     * @throws Exception
     */
    public function insertLog(string $trackName, ?string $exceptionMessage = null): void
    {
        $this->getConnection()->insert(
            'logs',
            [
                'track_name' => $trackName,
                'error_text' => $exceptionMessage,
                'created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
