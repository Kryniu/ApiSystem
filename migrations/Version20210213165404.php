<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210213165404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(
        /** @lang MySQL */ <<< SQL
CREATE TABLE book (
    id INT AUTO_INCREMENT NOT NULL, 
    title NVARCHAR(255) NOT NULL,
    description longtext DEFAULT NULL,
    short_description tinytext DEFAULT NULL,
    PRIMARY KEY(id)
) 
DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL
        );

        $this->addSql(
        /** @lang MySQL */ <<< SQL
CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL, 
    first_name NVARCHAR(50) NOT NULL, 
    last_name NVARCHAR(50) DEFAULT NULL, 
    email NVARCHAR(50) NOT NULL, 
    slug NVARCHAR(255) DEFAULT NULL, 
    UNIQUE INDEX UNIQ_user_email (email), 
    PRIMARY KEY(id)
) 
DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL
        );

        $this->addSql(
        /** @lang MySQL */ <<< SQL
CREATE TABLE book_user (
    book_id INT NOT NULL, 
    user_id INT NOT NULL, 
    INDEX IDX_bookUser_book_bookId (book_id), 
    INDEX IDX_bookUser_user_userId (user_id), 
    PRIMARY KEY(book_id, user_id)
) 
DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
SQL
        );

        $this->addSql(
        /** @lang MySQL */ <<< SQL
ALTER TABLE book_user ADD CONSTRAINT FK_bookUser_user_userId FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
ALTER TABLE book_user ADD CONSTRAINT FK_bookUser_book_bookId FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE
SQL
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql(
        /** @lang MySQL */ <<< SQL
ALTER TABLE book_user DROP FOREIGN KEY FK_bookUser_user_userId
ALTER TABLE book_user DROP FOREIGN KEY FK_bookUser_book_bookId
DROP INDEX IDX_bookUser_book_bookId ON book_user
DROP INDEX IDX_bookUser_user_userId ON book_user
DROP INDEX UNIQ_user_email ON user
SQL
        );

        $this->addSql(
        /** @lang MySQL */ <<< SQL
DROP TABLE book_user
DROP TABLE book
DROP TABLE user
SQL
        );
    }
}
