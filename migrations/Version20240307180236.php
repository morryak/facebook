<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307180236 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user
            (
                name       VARCHAR(50)                        NOT NULL,
                email      VARCHAR(100)                       NOT NULL,
                biography  VARCHAR(255)                       NOT NULL,
                last_name  VARCHAR(50)                        NOT NULL,
                password   VARCHAR(255)                       NOT NULL,
                sex        VARCHAR(10)                        NOT NULL,
                city       VARCHAR(50)                        NOT NULL,
                birth_date DATE                               NOT NULL,
                roles      LONGTEXT DEFAULT NULL,
                id         BIGINT UNSIGNED AUTO_INCREMENT     NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                UNIQUE INDEX email (email),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8
        ');
        $this->addSql('
            CREATE TABLE user_token
            (
                token      VARCHAR(255)                       NOT NULL,
                active     TINYINT(1)                         NOT NULL,
                started_at DATETIME                           NOT NULL,
                closed_at  DATETIME DEFAULT NULL,
                id         BIGINT UNSIGNED AUTO_INCREMENT     NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                user_id    BIGINT UNSIGNED                    NOT NULL,
                INDEX user_id (user_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
