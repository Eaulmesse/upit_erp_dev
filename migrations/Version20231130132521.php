<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130132521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id INT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, contact_name VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_zip_code VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_region VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) NOT NULL, is_for_invoice TINYINT(1) NOT NULL, is_for_delivery TINYINT(1) NOT NULL, is_for_quotation TINYINT(1) NOT NULL, INDEX IDX_6FCA7516979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE companies (id INT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATE DEFAULT NULL, address_street LONGTEXT DEFAULT NULL, address_zip_code INT DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_region LONGTEXT DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, is_supplier TINYINT(1) DEFAULT NULL, is_prospect TINYINT(1) DEFAULT NULL, is_customer TINYINT(1) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, thirdparty_code VARCHAR(255) DEFAULT NULL, intracommunity_number LONGTEXT DEFAULT NULL, supplier_thidparty_code VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, is_b2_c TINYINT(1) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contracts (id INT NOT NULL, user_id INT DEFAULT NULL, company_id INT DEFAULT NULL, name LONGTEXT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, comments LONGTEXT DEFAULT NULL, expected_delivery_date DATETIME DEFAULT NULL, first_invoice_planned_date DATETIME DEFAULT NULL, generate_and_send_recurring_invoices LONGTEXT DEFAULT NULL, invoice_frenquency_in_months LONGTEXT DEFAULT NULL, preauthorized_debit LONGTEXT DEFAULT NULL, last_update_date DATETIME DEFAULT NULL, INDEX IDX_950A973A76ED395 (user_id), INDEX IDX_950A973979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employees (id INT NOT NULL, company_id INT DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email LONGTEXT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, cellphone_number VARCHAR(255) DEFAULT NULL, job VARCHAR(255) DEFAULT NULL, is_billing_contact TINYINT(1) NOT NULL, INDEX IDX_BA82C300979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense_lines (id INT AUTO_INCREMENT NOT NULL, expenses_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, quantity INT DEFAULT NULL, total_pre_tax_amount DOUBLE PRECISION DEFAULT NULL, accounting_code VARCHAR(255) DEFAULT NULL, INDEX IDX_EC092BAC2055804A (expenses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expenses (id INT NOT NULL, supplier_id INT DEFAULT NULL, company_id INT DEFAULT NULL, workforce_id INT DEFAULT NULL, payslips_id INT DEFAULT NULL, project_id INT DEFAULT NULL, supplier_contract_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL, number VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, last_update_date DATETIME DEFAULT NULL, paid_date DATETIME DEFAULT NULL, expected_payment_date DATETIME DEFAULT NULL, pre_tax_amount DOUBLE PRECISION NOT NULL, tax_amount DOUBLE PRECISION NOT NULL, left_to_pay DOUBLE PRECISION DEFAULT NULL, currency VARCHAR(255) NOT NULL, accounting_code VARCHAR(255) DEFAULT NULL, accounting_code_name VARCHAR(255) DEFAULT NULL, public_path VARCHAR(255) DEFAULT NULL, supplier_name VARCHAR(255) DEFAULT NULL, total_amount DOUBLE PRECISION NOT NULL, INDEX IDX_2496F35B2ADD6D8C (supplier_id), INDEX IDX_2496F35B979B1AD6 (company_id), INDEX IDX_2496F35BA25BA942 (workforce_id), INDEX IDX_2496F35BAFEAE430 (payslips_id), INDEX IDX_2496F35B166D1F9C (project_id), INDEX IDX_2496F35BFCC14BEC (supplier_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_lines (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, tax_rates_id INT DEFAULT NULL, details LONGTEXT DEFAULT NULL, total_pre_tax_amount DOUBLE PRECISION NOT NULL, total_tax_amount DOUBLE PRECISION DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, chapter LONGTEXT DEFAULT NULL, discounts_amount DOUBLE PRECISION DEFAULT NULL, discounts_amount_with_tax DOUBLE PRECISION DEFAULT NULL, accounting_code LONGTEXT DEFAULT NULL, unit_job_costing DOUBLE PRECISION DEFAULT NULL, INDEX IDX_72DBDC234584665A (product_id), INDEX IDX_72DBDC2367E90BD1 (tax_rates_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoices (id INT NOT NULL, contracts_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, order_number VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, sent_date DATETIME DEFAULT NULL, due_date DATETIME DEFAULT NULL, paid_date DATETIME DEFAULT NULL, delivery_date DATETIME DEFAULT NULL, deposit_percent DOUBLE PRECISION DEFAULT NULL, deposit_flat DOUBLE PRECISION DEFAULT NULL, last_update_date DATETIME DEFAULT NULL, tax_amount DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, discounts_amount DOUBLE PRECISION NOT NULL, discounts_amount_with_tax DOUBLE PRECISION NOT NULL, discounts_comments LONGTEXT NOT NULL, taxes_rate DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, margin DOUBLE PRECISION NOT NULL, mandatory_mentions VARCHAR(255) DEFAULT NULL, payment_mentions LONGTEXT NOT NULL, theme_id INT NOT NULL, outstanding_amount DOUBLE PRECISION NOT NULL, frequency_in_months INT DEFAULT NULL, business_user VARCHAR(255) NOT NULL, public_path LONGTEXT NOT NULL, paid_invoice_pdf LONGTEXT DEFAULT NULL, customer_portal_url LONGTEXT DEFAULT NULL, pre_tax_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_6A2F2F9524584564 (contracts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunities (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, employees_id INT DEFAULT NULL, name LONGTEXT NOT NULL, comments LONGTEXT DEFAULT NULL, amount INT NOT NULL, probability DOUBLE PRECISION NOT NULL, due_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, is_win TINYINT(1) NOT NULL, is_archived TINYINT(1) NOT NULL, user_name VARCHAR(255) NOT NULL, pip_name VARCHAR(255) NOT NULL, pip_step_name VARCHAR(255) NOT NULL, INDEX IDX_406D4DB0979B1AD6 (company_id), INDEX IDX_406D4DB08520A30B (employees_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payslips (id INT NOT NULL, workforce_id INT DEFAULT NULL, date DATETIME NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, net_salary DOUBLE PRECISION NOT NULL, total_cost DOUBLE PRECISION NOT NULL, total_hours DOUBLE PRECISION NOT NULL, INDEX IDX_A6292EDAA25BA942 (workforce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_tax_amount (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, name LONGTEXT DEFAULT NULL, product_code LONGTEXT DEFAULT NULL, supplier_product_code LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_with_tax DOUBLE PRECISION DEFAULT NULL, tax_rate DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, job_costing DOUBLE PRECISION DEFAULT NULL, location LONGTEXT DEFAULT NULL, unit LONGTEXT DEFAULT NULL, disabled TINYINT(1) DEFAULT NULL, internal_id VARCHAR(255) DEFAULT NULL, stock INT DEFAULT NULL, weighted_average_cost DOUBLE PRECISION DEFAULT NULL, image LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_natures (id INT NOT NULL, is_disabled TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT NOT NULL, project_natures_id INT DEFAULT NULL, company_id INT DEFAULT NULL, statuses_id INT DEFAULT NULL, users_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, comments LONGTEXT DEFAULT NULL, estimated_hours VARCHAR(255) DEFAULT NULL, estimated_cost VARCHAR(255) DEFAULT NULL, estimated_revenue DOUBLE PRECISION DEFAULT NULL, actual_hours DOUBLE PRECISION NOT NULL, actual_expenses_cost DOUBLE PRECISION DEFAULT NULL, actual_timetrackings_cost DOUBLE PRECISION DEFAULT NULL, actual_consume_products_cost DOUBLE PRECISION DEFAULT NULL, actual_revenue DOUBLE PRECISION DEFAULT NULL, estimated_start DATETIME DEFAULT NULL, actual_start DATETIME DEFAULT NULL, estimated_end DATETIME DEFAULT NULL, actual_end DATETIME DEFAULT NULL, INDEX IDX_5C93B3A41EE29521 (project_natures_id), INDEX IDX_5C93B3A4979B1AD6 (company_id), INDEX IDX_5C93B3A41259C1FF (statuses_id), INDEX IDX_5C93B3A467B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_nature (id INT AUTO_INCREMENT NOT NULL, projet_nature_id INT DEFAULT NULL, INDEX IDX_937132F11835C99F (projet_nature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotation_lines (id INT AUTO_INCREMENT NOT NULL, quotations_id INT DEFAULT NULL, products_id INT DEFAULT NULL, product_internal_id INT DEFAULT NULL, product_name VARCHAR(255) NOT NULL, product_code LONGTEXT NOT NULL, title LONGTEXT NOT NULL, details LONGTEXT NOT NULL, unit LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, tax_rates DOUBLE PRECISION NOT NULL, tax_name VARCHAR(255) NOT NULL, line_discount_amount DOUBLE PRECISION NOT NULL, line_discount_amount_with_tax DOUBLE PRECISION NOT NULL, line_discount_unit_is_percent TINYINT(1) NOT NULL, tax_amount DOUBLE PRECISION NOT NULL, pre_tax_amount DOUBLE PRECISION NOT NULL, total_amount DOUBLE PRECISION NOT NULL, margin VARCHAR(255) NOT NULL, unit_job_costing INT NOT NULL, chapter LONGTEXT NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_30F67588AA773633 (quotations_id), INDEX IDX_30F675886C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotations (id INT NOT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, opportunitiy_id INT DEFAULT NULL, contract_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, expiry_date DATE NOT NULL, sent_date DATE DEFAULT NULL, last_update_date DATE DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, date_customer_answer DATE DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, global_discount_amount VARCHAR(255) DEFAULT NULL, global_discount_with_tax VARCHAR(255) DEFAULT NULL, global_discount_unit_is_percent VARCHAR(255) DEFAULT NULL, global_discount_comments LONGTEXT DEFAULT NULL, pre_tax_amount DOUBLE PRECISION DEFAULT NULL, tax_amount DOUBLE PRECISION DEFAULT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, margin DOUBLE PRECISION DEFAULT NULL, payments_to_display_in_pdf VARCHAR(255) DEFAULT NULL, signature_date DATE DEFAULT NULL, signature_timezone_type INT DEFAULT NULL, signature_timezone VARCHAR(255) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, public_path LONGTEXT DEFAULT NULL, customer_portal_url LONGTEXT DEFAULT NULL, INDEX IDX_A9F48EAE979B1AD6 (company_id), INDEX IDX_A9F48EAEA76ED395 (user_id), INDEX IDX_A9F48EAE166D1F9C (project_id), INDEX IDX_A9F48EAE7809E30B (opportunitiy_id), INDEX IDX_A9F48EAE2576E0FD (contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_contract (id INT NOT NULL, supplier_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, frequency_in_months INT DEFAULT NULL, comments LONGTEXT NOT NULL, pre_tax_amount DOUBLE PRECISION NOT NULL, total_amount DOUBLE PRECISION NOT NULL, INDEX IDX_640640D82ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suppliers (id INT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, prefered_tax_rate DOUBLE PRECISION DEFAULT NULL, thirdparty_code INT NOT NULL, UNIQUE INDEX UNIQ_AC28B95C979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_rates (id INT NOT NULL, name VARCHAR(255) NOT NULL, rate INT NOT NULL, for_sales TINYINT(1) NOT NULL, for_purchases TINYINT(1) NOT NULL, accounting_code_collected LONGTEXT DEFAULT NULL, accounting_code_deductible LONGTEXT DEFAULT NULL, is_expenses_intracommunity_tax_rate TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, cellphone_number VARCHAR(255) DEFAULT NULL, company_natures LONGTEXT DEFAULT NULL, roles LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workforces (id INT NOT NULL, email VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, birth DATETIME DEFAULT NULL, address_street LONGTEXT DEFAULT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, job LONGTEXT DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, entry_date DATETIME DEFAULT NULL, exit_date DATETIME DEFAULT NULL, thirdparty_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA7516979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contracts ADD CONSTRAINT FK_950A973979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE expense_lines ADD CONSTRAINT FK_EC092BAC2055804A FOREIGN KEY (expenses_id) REFERENCES expenses (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35B2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35B979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35BA25BA942 FOREIGN KEY (workforce_id) REFERENCES workforces (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35BAFEAE430 FOREIGN KEY (payslips_id) REFERENCES payslips (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35B166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35BFCC14BEC FOREIGN KEY (supplier_contract_id) REFERENCES supplier_contract (id)');
        $this->addSql('ALTER TABLE invoice_lines ADD CONSTRAINT FK_72DBDC234584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE invoice_lines ADD CONSTRAINT FK_72DBDC2367E90BD1 FOREIGN KEY (tax_rates_id) REFERENCES tax_rates (id)');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9524584564 FOREIGN KEY (contracts_id) REFERENCES contracts (id)');
        $this->addSql('ALTER TABLE opportunities ADD CONSTRAINT FK_406D4DB0979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE opportunities ADD CONSTRAINT FK_406D4DB08520A30B FOREIGN KEY (employees_id) REFERENCES employees (id)');
        $this->addSql('ALTER TABLE payslips ADD CONSTRAINT FK_A6292EDAA25BA942 FOREIGN KEY (workforce_id) REFERENCES workforces (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A41EE29521 FOREIGN KEY (project_natures_id) REFERENCES project_natures (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A41259C1FF FOREIGN KEY (statuses_id) REFERENCES statuses (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A467B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE projet_nature ADD CONSTRAINT FK_937132F11835C99F FOREIGN KEY (projet_nature_id) REFERENCES project_natures (id)');
        $this->addSql('ALTER TABLE quotation_lines ADD CONSTRAINT FK_30F67588AA773633 FOREIGN KEY (quotations_id) REFERENCES quotations (id)');
        $this->addSql('ALTER TABLE quotation_lines ADD CONSTRAINT FK_30F675886C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE7809E30B FOREIGN KEY (opportunitiy_id) REFERENCES opportunities (id)');
        $this->addSql('ALTER TABLE quotations ADD CONSTRAINT FK_A9F48EAE2576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id)');
        $this->addSql('ALTER TABLE supplier_contract ADD CONSTRAINT FK_640640D82ADD6D8C FOREIGN KEY (supplier_id) REFERENCES suppliers (id)');
        $this->addSql('ALTER TABLE suppliers ADD CONSTRAINT FK_AC28B95C979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA7516979B1AD6');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973A76ED395');
        $this->addSql('ALTER TABLE contracts DROP FOREIGN KEY FK_950A973979B1AD6');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300979B1AD6');
        $this->addSql('ALTER TABLE expense_lines DROP FOREIGN KEY FK_EC092BAC2055804A');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35B2ADD6D8C');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35B979B1AD6');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35BA25BA942');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35BAFEAE430');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35B166D1F9C');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35BFCC14BEC');
        $this->addSql('ALTER TABLE invoice_lines DROP FOREIGN KEY FK_72DBDC234584665A');
        $this->addSql('ALTER TABLE invoice_lines DROP FOREIGN KEY FK_72DBDC2367E90BD1');
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F9524584564');
        $this->addSql('ALTER TABLE opportunities DROP FOREIGN KEY FK_406D4DB0979B1AD6');
        $this->addSql('ALTER TABLE opportunities DROP FOREIGN KEY FK_406D4DB08520A30B');
        $this->addSql('ALTER TABLE payslips DROP FOREIGN KEY FK_A6292EDAA25BA942');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A41EE29521');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4979B1AD6');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A41259C1FF');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A467B3B43D');
        $this->addSql('ALTER TABLE projet_nature DROP FOREIGN KEY FK_937132F11835C99F');
        $this->addSql('ALTER TABLE quotation_lines DROP FOREIGN KEY FK_30F67588AA773633');
        $this->addSql('ALTER TABLE quotation_lines DROP FOREIGN KEY FK_30F675886C8A81A9');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE979B1AD6');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAEA76ED395');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE166D1F9C');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE7809E30B');
        $this->addSql('ALTER TABLE quotations DROP FOREIGN KEY FK_A9F48EAE2576E0FD');
        $this->addSql('ALTER TABLE supplier_contract DROP FOREIGN KEY FK_640640D82ADD6D8C');
        $this->addSql('ALTER TABLE suppliers DROP FOREIGN KEY FK_AC28B95C979B1AD6');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE contracts');
        $this->addSql('DROP TABLE employees');
        $this->addSql('DROP TABLE expense_lines');
        $this->addSql('DROP TABLE expenses');
        $this->addSql('DROP TABLE invoice_lines');
        $this->addSql('DROP TABLE invoices');
        $this->addSql('DROP TABLE opportunities');
        $this->addSql('DROP TABLE payslips');
        $this->addSql('DROP TABLE pre_tax_amount');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE project_natures');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projet_nature');
        $this->addSql('DROP TABLE quotation_lines');
        $this->addSql('DROP TABLE quotations');
        $this->addSql('DROP TABLE statuses');
        $this->addSql('DROP TABLE supplier_contract');
        $this->addSql('DROP TABLE suppliers');
        $this->addSql('DROP TABLE tax_rates');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE workforces');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
