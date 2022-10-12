<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221012080216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentary (
            id INT AUTO_INCREMENT NOT NULL, 
            user_id INT DEFAULT NULL, 
            product_id INT DEFAULT NULL, 
            comment LONGTEXT DEFAULT NULL, 
            created_at DATETIME DEFAULT NULL, 
            is_published TINYINT(1) DEFAULT NULL, 
            INDEX IDX_1CAC12CAA76ED395 (user_id), 
            INDEX IDX_1CAC12CA4584665A (product_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 
            COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentary 
        ADD CONSTRAINT FK_1CAC12CAA76ED395 
        FOREIGN KEY (user_id) 
        REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentary 
        ADD CONSTRAINT FK_1CAC12CA4584665A 
        FOREIGN KEY (product_id) 
        REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAA76ED395');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CA4584665A');
        $this->addSql('DROP TABLE commentary');
    }
}
