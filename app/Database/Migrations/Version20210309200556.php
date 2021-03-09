<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309200556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact_type (`key` VARCHAR(10) NOT NULL, display VARCHAR(255) NOT NULL, PRIMARY KEY(`key`)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_contacts (customer_id INT UNSIGNED NOT NULL, contact_id INT UNSIGNED NOT NULL, INDEX IDX_1F85565C9395C3F3 (customer_id), INDEX IDX_1F85565CE7A1254A (contact_id), PRIMARY KEY(customer_id, contact_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_contact (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type_key VARCHAR(10) DEFAULT NULL, value VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_50BF428688874D48 (type_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_contacts ADD CONSTRAINT FK_1F85565C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer_contacts ADD CONSTRAINT FK_1F85565CE7A1254A FOREIGN KEY (contact_id) REFERENCES customer_contact (id)');
        $this->addSql('ALTER TABLE customer_contact ADD CONSTRAINT FK_50BF428688874D48 FOREIGN KEY (type_key) REFERENCES contact_type (`key`)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_contact DROP FOREIGN KEY FK_50BF428688874D48');
        $this->addSql('ALTER TABLE customer_contacts DROP FOREIGN KEY FK_1F85565C9395C3F3');
        $this->addSql('ALTER TABLE customer_contacts DROP FOREIGN KEY FK_1F85565CE7A1254A');
        $this->addSql('DROP TABLE contact_type');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_contacts');
        $this->addSql('DROP TABLE customer_contact');
    }
}
