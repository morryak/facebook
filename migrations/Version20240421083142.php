<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240421083142 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE token
            (
                id         BIGINT UNSIGNED AUTO_INCREMENT     NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                token      VARCHAR(255)            NOT NULL,
                UNIQUE INDEX uniq_token (token),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
