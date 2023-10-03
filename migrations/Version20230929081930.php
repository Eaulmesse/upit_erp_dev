<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929081930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, company_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, contact_name VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_zip_code INT NOT NULL, address_city VARCHAR(255) NOT NULL, address_region VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) NOT NULL, is_for_invoice TINYINT(1) NOT NULL, is_for_delivery TINYINT(1) NOT NULL, is_for_quotation TINYINT(1) NOT NULL, INDEX IDX_6FCA751638B53C32 (company_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA751638B53C32 FOREIGN KEY (company_id_id) REFERENCES companies (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA751638B53C32');
        $this->addSql('DROP TABLE addresses');
    }
}
