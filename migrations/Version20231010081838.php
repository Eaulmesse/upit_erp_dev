<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010081838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT DEFAULT NULL, product_code LONGTEXT DEFAULT NULL, supplier_product_code LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_with_tax DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, job_costing DOUBLE PRECISION DEFAULT NULL, location LONGTEXT DEFAULT NULL, unit LONGTEXT DEFAULT NULL, disabled TINYINT(1) DEFAULT NULL, internal_id INT DEFAULT NULL, stock INT DEFAULT NULL, weighted_average_cost DOUBLE PRECISION DEFAULT NULL, image LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE products');
    }
}
