<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130130605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_lines ADD invoice_id INT DEFAULT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice_lines ADD CONSTRAINT FK_72DBDC232989F1FD FOREIGN KEY (invoice_id) REFERENCES invoices (id)');
        $this->addSql('CREATE INDEX IDX_72DBDC232989F1FD ON invoice_lines (invoice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_lines DROP FOREIGN KEY FK_72DBDC232989F1FD');
        $this->addSql('DROP INDEX IDX_72DBDC232989F1FD ON invoice_lines');
        $this->addSql('ALTER TABLE invoice_lines DROP invoice_id, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
