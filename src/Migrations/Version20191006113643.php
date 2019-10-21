<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191006113643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bottle (id INT AUTO_INCREMENT NOT NULL, wine_id INT NOT NULL, drinked_on DATETIME DEFAULT NULL, drink_before DATETIME DEFAULT NULL, INDEX IDX_ACA9A95528A2BD76 (wine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wine (id INT AUTO_INCREMENT NOT NULL, color VARCHAR(16) NOT NULL, origin VARCHAR(128) NOT NULL, year INT NOT NULL, rate INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bottle ADD CONSTRAINT FK_ACA9A95528A2BD76 FOREIGN KEY (wine_id) REFERENCES wine (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bottle DROP FOREIGN KEY FK_ACA9A95528A2BD76');
        $this->addSql('DROP TABLE bottle');
        $this->addSql('DROP TABLE wine');
    }
}
