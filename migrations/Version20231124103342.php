<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231124103342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet_nature (id INT AUTO_INCREMENT NOT NULL, projet_nature_id INT DEFAULT NULL, INDEX IDX_937132F11835C99F (projet_nature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet_nature ADD CONSTRAINT FK_937132F11835C99F FOREIGN KEY (projet_nature_id) REFERENCES project_natures (id)');
        $this->addSql('ALTER TABLE opportunities DROP employee_name, DROP employee_email, DROP employee_cellphone_number, DROP employee_phone_number');
        $this->addSql('ALTER TABLE products CHANGE internal_id internal_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_nature DROP FOREIGN KEY FK_937132F11835C99F');
        $this->addSql('DROP TABLE projet_nature');
        $this->addSql('ALTER TABLE opportunities ADD employee_name VARCHAR(255) NOT NULL, ADD employee_email VARCHAR(255) NOT NULL, ADD employee_cellphone_number VARCHAR(255) NOT NULL, ADD employee_phone_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE internal_id internal_id INT DEFAULT NULL');
    }
}
