<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107085432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payslips (id INT NOT NULL, workforce_id INT DEFAULT NULL, date DATETIME NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, net_salary DOUBLE PRECISION NOT NULL, total_cost DOUBLE PRECISION NOT NULL, total_hours DOUBLE PRECISION NOT NULL, INDEX IDX_A6292EDAA25BA942 (workforce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payslips ADD CONSTRAINT FK_A6292EDAA25BA942 FOREIGN KEY (workforce_id) REFERENCES workforces (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payslips DROP FOREIGN KEY FK_A6292EDAA25BA942');
        $this->addSql('DROP TABLE payslips');
    }
}
