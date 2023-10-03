<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927122617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, address_street LONGTEXT NOT NULL, address_zip_code INT NOT NULL, address_city VARCHAR(255) NOT NULL, address_region LONGTEXT DEFAULT NULL, address_country VARCHAR(255) NOT NULL, comments LONGTEXT DEFAULT NULL, is_supplier TINYINT(1) NOT NULL, is_prospect TINYINT(1) NOT NULL, is_customer TINYINT(1) NOT NULL, currency VARCHAR(255) NOT NULL, thirdparty_code INT NOT NULL, intracommunity_number LONGTEXT NOT NULL, supplier_thidparty_code INT DEFAULT NULL, siret INT NOT NULL, is_b2_c TINYINT(1) NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE companies');
    }
}
