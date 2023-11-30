<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130130018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice_lines (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, details LONGTEXT DEFAULT NULL, total_pre_tax_amount DOUBLE PRECISION NOT NULL, total_tax_amount DOUBLE PRECISION DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, chapter LONGTEXT DEFAULT NULL, discounts_amount DOUBLE PRECISION DEFAULT NULL, discounts_amount_with_tax DOUBLE PRECISION DEFAULT NULL, accounting_code LONGTEXT DEFAULT NULL, unit_job_costing DOUBLE PRECISION DEFAULT NULL, INDEX IDX_72DBDC234584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice_lines ADD CONSTRAINT FK_72DBDC234584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE tax_rates ADD invoice_lines_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tax_rates ADD CONSTRAINT FK_F7AE5E1D60C6BCB4 FOREIGN KEY (invoice_lines_id) REFERENCES invoice_lines (id)');
        $this->addSql('CREATE INDEX IDX_F7AE5E1D60C6BCB4 ON tax_rates (invoice_lines_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tax_rates DROP FOREIGN KEY FK_F7AE5E1D60C6BCB4');
        $this->addSql('ALTER TABLE invoice_lines DROP FOREIGN KEY FK_72DBDC234584665A');
        $this->addSql('DROP TABLE invoice_lines');
        $this->addSql('DROP INDEX IDX_F7AE5E1D60C6BCB4 ON tax_rates');
        $this->addSql('ALTER TABLE tax_rates DROP invoice_lines_id');
    }
}
