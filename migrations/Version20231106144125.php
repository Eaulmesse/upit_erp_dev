<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106144125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoices ADD contracts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F9524584564 FOREIGN KEY (contracts_id) REFERENCES contracts (id)');
        $this->addSql('CREATE INDEX IDX_6A2F2F9524584564 ON invoices (contracts_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F9524584564');
        $this->addSql('DROP INDEX IDX_6A2F2F9524584564 ON invoices');
        $this->addSql('ALTER TABLE invoices DROP contracts_id');
    }
}
