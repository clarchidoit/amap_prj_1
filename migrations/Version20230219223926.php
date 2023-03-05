<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219223926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD is_cotisation TINYINT(1) DEFAULT FALSE NOT NULL, DROP etat_cotisation');
        $this->addSql('ALTER TABLE user ADD is_actif TINYINT(1) DEFAULT FALSE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD etat_cotisation VARCHAR(20) NOT NULL, DROP is_cotisation');
        $this->addSql('ALTER TABLE user DROP is_actif');
    }
}
