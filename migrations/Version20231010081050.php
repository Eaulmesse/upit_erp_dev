<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010081050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, product_code LONGTEXT NOT NULL, supplier_product_code LONGTEXT NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, price_with_tax DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, job_costing DOUBLE PRECISION NOT NULL, location LONGTEXT NOT NULL, unit LONGTEXT NOT NULL, disabled TINYINT(1) NOT NULL, internal_id INT DEFAULT NULL, stock INT NOT NULL, weighted_average_cost DOUBLE PRECISION NOT NULL, image LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE products');
    }
}
