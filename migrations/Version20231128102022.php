<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128102022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE invoices DROP billing_address_street, DROP billing_address_city, DROP delivery_address_street, DROP delivery_address_city, DROP parent_project, DROP son_projects');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE invoices ADD billing_address_street VARCHAR(255) DEFAULT NULL, ADD billing_address_city VARCHAR(255) DEFAULT NULL, ADD delivery_address_street VARCHAR(255) DEFAULT NULL, ADD delivery_address_city VARCHAR(255) DEFAULT NULL, ADD parent_project VARCHAR(255) DEFAULT NULL, ADD son_projects VARCHAR(255) DEFAULT NULL');
    }
}
