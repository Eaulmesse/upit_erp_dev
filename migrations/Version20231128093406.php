<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128093406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts DROP invoice_address_street, DROP invoice_address_city, DROP delivery_address_street, DROP delivery_address_city');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts ADD invoice_address_street VARCHAR(255) NOT NULL, ADD invoice_address_city VARCHAR(255) NOT NULL, ADD delivery_address_street VARCHAR(255) DEFAULT NULL, ADD delivery_address_city VARCHAR(255) DEFAULT NULL');
    }
}
