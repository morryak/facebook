<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240421083149 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE feed
            (
                text       LONGTEXT NOT NULL,
                id         BIGINT UNSIGNED AUTO_INCREMENT     NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                user_id    BIGINT UNSIGNED                    NOT NULL,
                INDEX user_id (user_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8
        ');
        $this->addSql('
            CREATE TABLE friends_list
            (
                id         BIGINT UNSIGNED AUTO_INCREMENT     NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                user_id    BIGINT UNSIGNED                    NOT NULL,
                friend_id  BIGINT UNSIGNED                    NOT NULL,
                UNIQUE INDEX uniq_user_id_friend_id (user_id, friend_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8
        ');
    }

    public function down(Schema $schema): void
    {
    }
}
