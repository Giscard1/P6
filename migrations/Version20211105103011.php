<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211105103011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME DEFAULT NULL, DROP up_date_date');
        $this->addSql('ALTER TABLE pictures ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE steps ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tricks ADD creation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(255) NOT NULL, ADD creation_date DATETIME NOT NULL, ADD update_date DATETIME DEFAULT NULL, ADD password VARCHAR(255) NOT NULL, ADD role TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP creation_date, DROP update_date');
        $this->addSql('ALTER TABLE comment ADD up_date_date DATE NOT NULL, DROP creation_date, DROP update_date');
        $this->addSql('ALTER TABLE pictures DROP creation_date, DROP update_date');
        $this->addSql('ALTER TABLE steps DROP creation_date, DROP update_date');
        $this->addSql('ALTER TABLE tricks DROP creation_date');
        $this->addSql('ALTER TABLE `user` DROP email, DROP creation_date, DROP update_date, DROP password, DROP role');
    }
}
