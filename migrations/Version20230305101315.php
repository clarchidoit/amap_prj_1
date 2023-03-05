<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305101315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent CHANGE is_cotisation is_cotisation TINYINT(1) DEFAULT FALSE NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_valide TINYINT(1) DEFAULT FALSE NOT NULL, ADD is_mail_valide TINYINT(1) DEFAULT FALSE NOT NULL, DROP is_actif');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent CHANGE is_cotisation is_cotisation TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_actif TINYINT(1) DEFAULT 0 NOT NULL, DROP is_valide, DROP is_mail_valide');
    }
}
