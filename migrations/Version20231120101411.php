<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120101411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet_nature (id INT AUTO_INCREMENT NOT NULL, projet_nature_id INT DEFAULT NULL, INDEX IDX_937132F11835C99F (projet_nature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_rates (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rate INT NOT NULL, for_sales TINYINT(1) NOT NULL, for_purchases TINYINT(1) NOT NULL, accounting_code_collected LONGTEXT DEFAULT NULL, accounting_code_deductible LONGTEXT DEFAULT NULL, is_expenses_intracommunity_tax_rate TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet_nature ADD CONSTRAINT FK_937132F11835C99F FOREIGN KEY (projet_nature_id) REFERENCES project_natures (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_nature DROP FOREIGN KEY FK_937132F11835C99F');
        $this->addSql('DROP TABLE projet_nature');
        $this->addSql('DROP TABLE tax_rates');
    }
}
