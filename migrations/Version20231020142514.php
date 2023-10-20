<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020142514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id INT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, contact_name VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_zip_code VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_region VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) NOT NULL, is_for_invoice TINYINT(1) NOT NULL, is_for_delivery TINYINT(1) NOT NULL, is_for_quotation TINYINT(1) NOT NULL, INDEX IDX_6FCA7516979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companies (id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, address_street LONGTEXT DEFAULT NULL, address_zip_code INT DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_region LONGTEXT DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, is_supplier TINYINT(1) DEFAULT NULL, is_prospect TINYINT(1) DEFAULT NULL, is_customer TINYINT(1) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, thirdparty_code INT DEFAULT NULL, intracommunity_number LONGTEXT DEFAULT NULL, supplier_thidparty_code INT DEFAULT NULL, siret INT DEFAULT NULL, is_b2_c TINYINT(1) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contracts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name LONGTEXT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, comments LONGTEXT DEFAULT NULL, expected_delivery_date DATETIME DEFAULT NULL, company_name VARCHAR(255) NOT NULL, contact_name VARCHAR(255) NOT NULL, street LONGTEXT NOT NULL, zip_code INT NOT NULL, city LONGTEXT NOT NULL, region LONGTEXT DEFAULT NULL, country LONGTEXT NOT NULL, delivery_address LONGTEXT DEFAULT NULL, first_invoice_planned_date DATETIME DEFAULT NULL, generate_and_send_recurring_invoices LONGTEXT DEFAULT NULL, invoice_frenquency_in_months LONGTEXT DEFAULT NULL, preauthorized_debit LONGTEXT DEFAULT NULL, INDEX IDX_950A973A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employees (id INT NOT NULL, company_id INT DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email LONGTEXT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, cellphone_number VARCHAR(255) DEFAULT NULL, job VARCHAR(255) DEFAULT NULL, is_billing_contact TINYINT(1) NOT NULL, INDEX IDX_BA82C300979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunities (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, employees_id INT DEFAULT NULL, name LONGTEXT NOT NULL, comments LONGTEXT DEFAULT NULL, amount INT NOT NULL, probability DOUBLE PRECISION NOT NULL, due_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, is_win TINYINT(1) NOT NULL, is_archived TINYINT(1) NOT NULL, user_name VARCHAR(255) NOT NULL, pip_name VARCHAR(255) NOT NULL, pip_step_name VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, company_is_supplier TINYINT(1) NOT NULL, company_is_prospect TINYINT(1) NOT NULL, company_is_customer TINYINT(1) NOT NULL, employee_name VARCHAR(255) NOT NULL, employee_email VARCHAR(255) NOT NULL, employee_cellphone_number VARCHAR(255) NOT NULL, employee_phone_number VARCHAR(255) NOT NULL, INDEX IDX_406D4DB0979B1AD6 (company_id), INDEX IDX_406D4DB08520A30B (employees_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payslips (id INT NOT NULL, workforce_id INT DEFAULT NULL, workforces_id INT DEFAULT NULL, date DATETIME NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, net_salary DOUBLE PRECISION NOT NULL, total_cost DOUBLE PRECISION NOT NULL, total_hours DOUBLE PRECISION NOT NULL, INDEX IDX_A6292EDAA25BA942 (workforce_id), INDEX IDX_A6292EDA7F01C630 (workforces_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, name LONGTEXT DEFAULT NULL, product_code LONGTEXT DEFAULT NULL, supplier_product_code LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_with_tax DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, job_costing DOUBLE PRECISION DEFAULT NULL, location LONGTEXT DEFAULT NULL, unit LONGTEXT DEFAULT NULL, disabled TINYINT(1) DEFAULT NULL, internal_id INT DEFAULT NULL, stock INT DEFAULT NULL, weighted_average_cost DOUBLE PRECISION DEFAULT NULL, image LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotation_lines (id INT AUTO_INCREMENT NOT NULL, quotations_id INT DEFAULT NULL, products_id INT DEFAULT NULL, product_internal_id INT DEFAULT NULL, product_name VARCHAR(255) NOT NULL, product_code LONGTEXT NOT NULL, title LONGTEXT NOT NULL, details LONGTEXT NOT NULL, unit LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, tax_rates DOUBLE PRECISION NOT NULL, tax_name VARCHAR(255) NOT NULL, line_discount_amount DOUBLE PRECISION NOT NULL, line_discount_amount_with_tax DOUBLE PRECISION NOT NULL, line_discount_unit_is_percent TINYINT(1) NOT NULL, tax_amount DOUBLE PRECISION NOT NULL, pre_tax_amount DOUBLE PRECISION NOT NULL, total_amount DOUBLE PRECISION NOT NULL, margin VARCHAR(255) NOT NULL, unit_job_costing INT NOT NULL, chapter LONGTEXT NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_30F67588AA773633 (quotations_id), INDEX IDX_30F675886C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotations (id INT NOT NULL, company_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, expiry_date DATE NOT NULL, sent_date DATE NOT NULL, last_update_date DATE DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, date_customer_answer DATE DEFAULT NULL, company_name VARCHAR(255) NOT NULL, global_discount_amount VARCHAR(255) DEFAULT NULL, global_discount_with_tax VARCHAR(255) DEFAULT NULL, global_discount_unit_is_percent VARCHAR(255) DEFAULT NULL, global_discount_comments LONGTEXT DEFAULT NULL, pre_tax_amount DOUBLE PRECISION DEFAULT NULL, tax_amount DOUBLE PRECISION DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, margin DOUBLE PRECISION DEFAULT NULL, payments_to_display_in_pdf VARCHAR(255) DEFAULT NULL, signature_date DATE DEFAULT NULL, signature_timezone_type INT DEFAULT NULL, signature_timezone VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, public_path LONGTEXT DEFAULT NULL, customer_portal_url LONGTEXT DEFAULT NULL, INDEX IDX_A9F48EAE979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, cellphone_number VARCHAR(255) DEFAULT NULL, company_natures LONGTEXT DEFAULT NULL, roles LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workforces (id INT NOT NULL, email VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, birth DATETIME DEFAULT NULL, address_street LONGTEXT DEFAULT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, job LONGTEXT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, entry_date DATETIME DEFAULT NULL, exit_date DATETIME DEFAULT NULL, thirdparty_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE opportunities ADD CONSTRAINT FK_406D4DB0979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE opportunities ADD CONSTRAINT FK_406D4DB08520A30B FOREIGN KEY (employees_id) REFERENCES employees (id)');
        $this->addSql('ALTER TABLE payslips ADD CONSTRAINT FK_A6292EDAA25BA942 FOREIGN KEY (workforce_id) REFERENCES workforces (id)');
        $this->addSql('ALTER TABLE payslips ADD CONSTRAINT FK_A6292EDA7F01C630 FOREIGN KEY (workforces_id) REFERENCES workforces (id)');
        $this->addSql('ALTER TABLE quotation_lines ADD CONSTRAINT FK_30F67588AA773633 FOREIGN KEY (quotations_id) REFERENCES quotations (id)');
        $this->addSql('ALTER TABLE quotation_lines ADD CONSTRAINT FK_30F675886C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA7516979B1AD6');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973A76ED395');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300979B1AD6');
        $this->addSql('ALTER TABLE opportunities DROP FOREIGN KEY FK_406D4DB0979B1AD6');
        $this->addSql('ALTER TABLE opportunities DROP FOREIGN KEY FK_406D4DB08520A30B');
        $this->addSql('ALTER TABLE payslips DROP FOREIGN KEY FK_A6292EDAA25BA942');
        $this->addSql('ALTER TABLE payslips DROP FOREIGN KEY FK_A6292EDA7F01C630');
        $this->addSql('ALTER TABLE quotation_lines DROP FOREIGN KEY FK_30F67588AA773633');
        $this->addSql('ALTER TABLE quotation_lines DROP FOREIGN KEY FK_30F675886C8A81A9');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE979B1AD6');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE contracts');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE opportunities');
        $this->addSql('DROP TABLE payslips');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE quotation_lines');
        $this->addSql('DROP TABLE quotations');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE workforces');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
