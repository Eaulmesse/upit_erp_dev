<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106135916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoices (id INT AUTO_INCREMENT NOT NULL, contract_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, order_number VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, sent_date DATETIME NOT NULL, due_date DATETIME NOT NULL, paid_date DATETIME DEFAULT NULL, delivery_date DATETIME DEFAULT NULL, deposit_percent DOUBLE PRECISION DEFAULT NULL, deposit_flat DOUBLE PRECISION DEFAULT NULL, last_update_date DATETIME DEFAULT NULL, tax_amount DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, discounts_amount DOUBLE PRECISION NOT NULL, discounts_amount_with_tax DOUBLE PRECISION NOT NULL, discounts_comments LONGTEXT NOT NULL, taxes_rate DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, margin DOUBLE PRECISION NOT NULL, mandatory_mentions VARCHAR(255) DEFAULT NULL, payment_mentions LONGTEXT NOT NULL, theme_id INT NOT NULL, outstanding_amount DOUBLE PRECISION NOT NULL, frequency_in_months INT NOT NULL, business_user VARCHAR(255) NOT NULL, public_path LONGTEXT NOT NULL, paid_invoice_pdf LONGTEXT DEFAULT NULL, customer_portal_url LONGTEXT DEFAULT NULL, billing_address_street VARCHAR(255) DEFAULT NULL, billing_address_city VARCHAR(255) DEFAULT NULL, delivery_address_street VARCHAR(255) DEFAULT NULL, delivery_address_city VARCHAR(255) DEFAULT NULL, parent_project VARCHAR(255) DEFAULT NULL, son_projects VARCHAR(255) DEFAULT NULL, INDEX IDX_6A2F2F952576E0FD (contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, comments LONGTEXT DEFAULT NULL, estimated_hours VARCHAR(255) DEFAULT NULL, estimated_cost VARCHAR(255) DEFAULT NULL, estimated_revenue DOUBLE PRECISION DEFAULT NULL, actual_hours DOUBLE PRECISION NOT NULL, actual_expenses_cost DOUBLE PRECISION DEFAULT NULL, actual_timetrackings_cost DOUBLE PRECISION DEFAULT NULL, actual_consume_products_cost DOUBLE PRECISION DEFAULT NULL, actual_revenue DOUBLE PRECISION DEFAULT NULL, estimated_start DATETIME DEFAULT NULL, actual_start DATETIME DEFAULT NULL, estimated_end DATETIME DEFAULT NULL, actual_end DATETIME DEFAULT NULL, project_nature VARCHAR(255) DEFAULT NULL, statuses_id INT NOT NULL, statuses_name VARCHAR(255) NOT NULL, statuses_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_companies (projects_id INT NOT NULL, companies_id INT NOT NULL, INDEX IDX_5BF86D051EDE0F55 (projects_id), INDEX IDX_5BF86D056AE4741E (companies_id), PRIMARY KEY(projects_id, companies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_workforces (projects_id INT NOT NULL, workforces_id INT NOT NULL, INDEX IDX_3A50093B1EDE0F55 (projects_id), INDEX IDX_3A50093B7F01C630 (workforces_id), PRIMARY KEY(projects_id, workforces_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F952576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id)');
        $this->addSql('ALTER TABLE projects_companies ADD CONSTRAINT FK_5BF86D051EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_companies ADD CONSTRAINT FK_5BF86D056AE4741E FOREIGN KEY (companies_id) REFERENCES companies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_workforces ADD CONSTRAINT FK_3A50093B1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_workforces ADD CONSTRAINT FK_3A50093B7F01C630 FOREIGN KEY (workforces_id) REFERENCES workforces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contracts ADD company_id INT DEFAULT NULL, ADD quotation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973B4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotations (id)');
        $this->addSql('CREATE INDEX IDX_950A973979B1AD6 ON contracts (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_950A973B4EA4E60 ON contracts (quotation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F952576E0FD');
        $this->addSql('ALTER TABLE projects_companies DROP FOREIGN KEY FK_5BF86D051EDE0F55');
        $this->addSql('ALTER TABLE projects_companies DROP FOREIGN KEY FK_5BF86D056AE4741E');
        $this->addSql('ALTER TABLE projects_workforces DROP FOREIGN KEY FK_3A50093B1EDE0F55');
        $this->addSql('ALTER TABLE projects_workforces DROP FOREIGN KEY FK_3A50093B7F01C630');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_companies');
        $this->addSql('DROP TABLE projects_workforces');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973979B1AD6');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973B4EA4E60');
        $this->addSql('DROP INDEX IDX_950A973979B1AD6 ON contracts');
        $this->addSql('DROP INDEX UNIQ_950A973B4EA4E60 ON contracts');
        $this->addSql('ALTER TABLE contracts DROP company_id, DROP quotation_id');
    }
}
