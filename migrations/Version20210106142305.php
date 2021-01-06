<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106142305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, short_description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_books (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_AD6C8EDBA76ED395 (user_id), INDEX IDX_AD6C8EDB16A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_books ADD CONSTRAINT FK_AD6C8EDBA76ED395 FOREIGN KEY (user_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE users_books ADD CONSTRAINT FK_AD6C8EDB16A2B381 FOREIGN KEY (book_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_books DROP FOREIGN KEY FK_AD6C8EDBA76ED395');
        $this->addSql('ALTER TABLE users_books DROP FOREIGN KEY FK_AD6C8EDB16A2B381');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE users_books');
        $this->addSql('DROP TABLE user');
    }
}
