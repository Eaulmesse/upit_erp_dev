<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124103509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunities DROP company_name, DROP company_is_supplier, DROP company_is_prospect, DROP company_is_customer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opportunities ADD company_name VARCHAR(255) NOT NULL, ADD company_is_supplier TINYINT(1) NOT NULL, ADD company_is_prospect TINYINT(1) NOT NULL, ADD company_is_customer TINYINT(1) NOT NULL');
    }
}
