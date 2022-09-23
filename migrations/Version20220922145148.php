<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922145148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address 
        (id INT AUTO_INCREMENT NOT NULL, 
        user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, 
        adresse LONGTEXT DEFAULT NULL, 
        complement_adresse LONGTEXT DEFAULT NULL, 
        zipcode VARCHAR(255) DEFAULT NULL, 
        city VARCHAR(255) DEFAULT NULL, 
        country VARCHAR(255) DEFAULT NULL, 
        phone VARCHAR(255) DEFAULT NULL, 
        is_active TINYINT(1) DEFAULT NULL, 
        INDEX IDX_D4E6F81A76ED395 (user_id), 
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address 
        ADD CONSTRAINT FK_D4E6F81A76ED395 
        FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP adress, DROP city, DROP zipcode, DROP country, DROP phone');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395');
        $this->addSql('DROP TABLE address');
        $this->addSql('ALTER TABLE user ADD 
        adress VARCHAR(255) DEFAULT NULL, 
        ADD city VARCHAR(255) DEFAULT NULL, 
        ADD zipcode VARCHAR(255) DEFAULT NULL, 
        ADD country VARCHAR(255) DEFAULT NULL, 
        ADD phone VARCHAR(255) DEFAULT NULL');
    }
}
