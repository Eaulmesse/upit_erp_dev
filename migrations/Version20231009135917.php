<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009135917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contracts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, company_id INT DEFAULT NULL, quotation_id INT DEFAULT NULL, name LONGTEXT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, comments LONGTEXT DEFAULT NULL, expected_delivery_date DATETIME DEFAULT NULL, company_name VARCHAR(255) NOT NULL, contact_name VARCHAR(255) NOT NULL, street LONGTEXT NOT NULL, zip_code INT NOT NULL, city LONGTEXT NOT NULL, region LONGTEXT DEFAULT NULL, country LONGTEXT NOT NULL, delivery_address LONGTEXT DEFAULT NULL, first_invoice_planned_date DATETIME DEFAULT NULL, generate_and_send_recurring_invoices LONGTEXT DEFAULT NULL, invoice_frenquency_in_months LONGTEXT DEFAULT NULL, preauthorized_debit LONGTEXT DEFAULT NULL, project LONGTEXT DEFAULT NULL, INDEX IDX_950A973A76ED395 (user_id), INDEX IDX_950A973979B1AD6 (company_id), UNIQUE INDEX UNIQ_950A973B4EA4E60 (quotation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunities (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, employees_id INT DEFAULT NULL, name LONGTEXT NOT NULL, comments LONGTEXT DEFAULT NULL, amount INT NOT NULL, probability DOUBLE PRECISION NOT NULL, due_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, is_win TINYINT(1) NOT NULL, is_archived TINYINT(1) NOT NULL, user_name VARCHAR(255) NOT NULL, pip_name VARCHAR(255) NOT NULL, pip_step_name VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, company_is_supplier TINYINT(1) NOT NULL, company_is_prospect TINYINT(1) NOT NULL, company_is_customer TINYINT(1) NOT NULL, employee_name VARCHAR(255) NOT NULL, employee_email VARCHAR(255) NOT NULL, employee_cellphone_number VARCHAR(255) NOT NULL, employee_phone_number VARCHAR(255) NOT NULL, INDEX IDX_406D4DB0979B1AD6 (company_id), INDEX IDX_406D4DB08520A30B (employees_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, quotation_lines_id INT DEFAULT NULL, name LONGTEXT NOT NULL, product_code LONGTEXT NOT NULL, supplier_product_code LONGTEXT NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, price_with_tax DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, job_costing DOUBLE PRECISION NOT NULL, location LONGTEXT NOT NULL, unit LONGTEXT NOT NULL, disabled TINYINT(1) NOT NULL, internal_id INT DEFAULT NULL, stock INT NOT NULL, weighted_average_cost DOUBLE PRECISION NOT NULL, image LONGTEXT DEFAULT NULL, INDEX IDX_B3BA5A5A8FF155A2 (quotation_lines_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, companies_id INT DEFAULT NULL, workforce_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, comments LONGTEXT DEFAULT NULL, estimated_hours DATETIME DEFAULT NULL, estimated_cost DOUBLE PRECISION DEFAULT NULL, estimated_revenue DOUBLE PRECISION DEFAULT NULL, actual_hours DOUBLE PRECISION DEFAULT NULL, actual_expense_cost DOUBLE PRECISION DEFAULT NULL, actual_timetrackings_cost DOUBLE PRECISION DEFAULT NULL, actual_consume_products_cost DOUBLE PRECISION DEFAULT NULL, actual_revenues DOUBLE PRECISION DEFAULT NULL, estimated_start DATETIME DEFAULT NULL, actual_start VARCHAR(255) DEFAULT NULL, estimated_end DATETIME DEFAULT NULL, actuel_end DATETIME DEFAULT NULL, project_name VARCHAR(255) DEFAULT NULL, statues VARCHAR(255) DEFAULT NULL, parent_project VARCHAR(255) DEFAULT NULL, son_projects VARCHAR(255) DEFAULT NULL, INDEX IDX_5C93B3A46AE4741E (companies_id), INDEX IDX_5C93B3A4A25BA942 (workforce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotation_lines (id INT AUTO_INCREMENT NOT NULL, product_internal_id INT DEFAULT NULL, product_name VARCHAR(255) NOT NULL, product_code LONGTEXT NOT NULL, title LONGTEXT NOT NULL, details LONGTEXT NOT NULL, unit LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, tax_rates DOUBLE PRECISION NOT NULL, tax_name VARCHAR(255) NOT NULL, line_discount_amount DOUBLE PRECISION NOT NULL, line_discount_amount_with_tax DOUBLE PRECISION NOT NULL, line_discount_unit_is_percent TINYINT(1) NOT NULL, tax_amount DOUBLE PRECISION NOT NULL, pre_tax_amount DOUBLE PRECISION NOT NULL, total_amount DOUBLE PRECISION NOT NULL, margin VARCHAR(255) NOT NULL, unit_job_costing INT NOT NULL, chapter LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotations (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, users_id INT DEFAULT NULL, projects_id INT DEFAULT NULL, opportunities_id INT DEFAULT NULL, contract_id INT DEFAULT NULL, quotation_line_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, expiry_date DATE NOT NULL, sent_date DATE NOT NULL, last_update_date DATE DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, date_customer_answer DATE DEFAULT NULL, company_name VARCHAR(255) NOT NULL, global_discount_amount VARCHAR(255) DEFAULT NULL, global_discount_with_tax VARCHAR(255) DEFAULT NULL, global_discount_unit_is_percent VARCHAR(255) DEFAULT NULL, global_discount_comments LONGTEXT DEFAULT NULL, pre_tax_amount DOUBLE PRECISION DEFAULT NULL, tax_amount DOUBLE PRECISION DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, margin DOUBLE PRECISION DEFAULT NULL, payments_to_display_in_pdf VARCHAR(255) DEFAULT NULL, signature_date DATE DEFAULT NULL, signature_timezone_type INT DEFAULT NULL, signature_timezone VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, public_path LONGTEXT DEFAULT NULL, customer_portal_url LONGTEXT DEFAULT NULL, INDEX IDX_A9F48EAE979B1AD6 (company_id), INDEX IDX_A9F48EAE67B3B43D (users_id), UNIQUE INDEX UNIQ_A9F48EAE1EDE0F55 (projects_id), UNIQUE INDEX UNIQ_A9F48EAED5CB17CD (opportunities_id), UNIQUE INDEX UNIQ_A9F48EAE2576E0FD (contract_id), UNIQUE INDEX UNIQ_A9F48EAE5D5B7FD2 (quotation_line_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, cellphone_number VARCHAR(255) NOT NULL, company_natures LONGTEXT NOT NULL, roles LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workforces (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birth DATETIME DEFAULT NULL, address_street LONGTEXT NOT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, job LONGTEXT NOT NULL, phone VARCHAR(255) NOT NULL, entry_date DATETIME NOT NULL, exit_date DATETIME DEFAULT NULL, thirdparty_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973B4EA4E60 FOREIGN KEY (quotation_id) REFERENCES quotations (id)');
        $this->addSql('ALTER TABLE opportunities ADD CONSTRAINT FK_406D4DB0979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE opportunities ADD CONSTRAINT FK_406D4DB08520A30B FOREIGN KEY (employees_id) REFERENCES employees (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8FF155A2 FOREIGN KEY (quotation_lines_id) REFERENCES quotation_lines (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A46AE4741E FOREIGN KEY (companies_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4A25BA942 FOREIGN KEY (workforce_id) REFERENCES workforces (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAED5CB17CD FOREIGN KEY (opportunities_id) REFERENCES opportunities (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE2576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE5D5B7FD2 FOREIGN KEY (quotation_line_id) REFERENCES quotation_lines (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973A76ED395');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973979B1AD6');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973B4EA4E60');
        $this->addSql('ALTER TABLE opportunities DROP FOREIGN KEY FK_406D4DB0979B1AD6');
        $this->addSql('ALTER TABLE opportunities DROP FOREIGN KEY FK_406D4DB08520A30B');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A8FF155A2');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A46AE4741E');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4A25BA942');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE979B1AD6');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE67B3B43D');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE1EDE0F55');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAED5CB17CD');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE2576E0FD');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE5D5B7FD2');
        $this->addSql('DROP TABLE contracts');
        $this->addSql('DROP TABLE opportunities');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE quotation_lines');
        $this->addSql('DROP TABLE quotations');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE workforces');
    }
}
