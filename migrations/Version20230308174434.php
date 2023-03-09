<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308174434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent CHANGE is_cotisation is_cotisation TINYINT(1) DEFAULT FALSE NOT NULL');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(100) NOT NULL, CHANGE is_valide is_valide TINYINT(1) DEFAULT FALSE NOT NULL, CHANGE is_mail_valide is_mail_valide TINYINT(1) DEFAULT FALSE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent CHANGE is_cotisation is_cotisation TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user DROP reset_token, CHANGE is_valide is_valide TINYINT(1) DEFAULT 0 NOT NULL, CHANGE is_mail_valide is_mail_valide TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
