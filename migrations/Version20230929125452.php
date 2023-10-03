<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929125452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA751638B53C32');
        $this->addSql('DROP INDEX IDX_6FCA751638B53C32 ON addresses');
        $this->addSql('ALTER TABLE addresses CHANGE company_id company_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA751638B53C32 FOREIGN KEY (company_id_id) REFERENCES companies (id)');
        $this->addSql('CREATE INDEX IDX_6FCA751638B53C32 ON addresses (company_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY FK_6FCA751638B53C32');
        $this->addSql('DROP INDEX IDX_6FCA751638B53C32 ON addresses');
        $this->addSql('ALTER TABLE addresses CHANGE company_id_id company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT FK_6FCA751638B53C32 FOREIGN KEY (company_id) REFERENCES companies (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6FCA751638B53C32 ON addresses (company_id)');
    }
}
