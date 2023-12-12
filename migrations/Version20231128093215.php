<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128093215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts DROP street, DROP zip_code, DROP city, DROP region, DROP country');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contracts ADD street LONGTEXT NOT NULL, ADD zip_code INT NOT NULL, ADD city LONGTEXT NOT NULL, ADD region LONGTEXT DEFAULT NULL, ADD country LONGTEXT NOT NULL');
    }
}
