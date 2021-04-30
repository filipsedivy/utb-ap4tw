<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429100446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE file_system (id INT UNSIGNED AUTO_INCREMENT NOT NULL, employee_id INT UNSIGNED DEFAULT NULL, size INT UNSIGNED NOT NULL, path VARCHAR(500) NOT NULL, content_type VARCHAR(255) DEFAULT \'application/octet-stream\' NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_715A37348C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_system ADD CONSTRAINT FK_715A37348C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE file_system');
    }
}
