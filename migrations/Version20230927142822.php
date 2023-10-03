<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927142822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, address_street LONGTEXT DEFAULT NULL, address_zip_code INT DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_region LONGTEXT DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, is_supplier TINYINT(1) DEFAULT NULL, is_prospect TINYINT(1) DEFAULT NULL, is_customer TINYINT(1) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, thirdparty_code INT DEFAULT NULL, intracommunity_number LONGTEXT DEFAULT NULL, supplier_thidparty_code INT DEFAULT NULL, siret INT DEFAULT NULL, is_b2_c TINYINT(1) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE companies');
    }
}
