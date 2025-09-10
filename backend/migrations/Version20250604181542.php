<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604181542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD toGainMuscles TINYINT(1) NOT NULL, ADD toLoseWeight TINYINT(1) NOT NULL, ADD fixShoulder TINYINT(1) NOT NULL, ADD fixKnees TINYINT(1) NOT NULL, ADD fixBack TINYINT(1) NOT NULL, ADD rehab TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP toGainMuscles, DROP toLoseWeight, DROP fixShoulder, DROP fixKnees, DROP fixBack, DROP rehab');
    }
}
