<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250605153519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet ADD toGainMuscles TINYINT(1) NOT NULL, ADD toMaintainWeight TINYINT(1) NOT NULL, ADD toLoseWeight TINYINT(1) NOT NULL, ADD toImproveMentalHealth TINYINT(1) NOT NULL, ADD toImprovePhysicalHealth TINYINT(1) NOT NULL, ADD fixShoulder TINYINT(1) NOT NULL, ADD fixKnees TINYINT(1) NOT NULL, ADD fixBack TINYINT(1) NOT NULL, ADD rehab TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE routine ADD toGainMuscles TINYINT(1) NOT NULL, ADD toMaintainWeight TINYINT(1) NOT NULL, ADD toLoseWeight TINYINT(1) NOT NULL, ADD toImproveMentalHealth TINYINT(1) NOT NULL, ADD toImprovePhysicalHealth TINYINT(1) NOT NULL, ADD fixShoulder TINYINT(1) NOT NULL, ADD fixKnees TINYINT(1) NOT NULL, ADD fixBack TINYINT(1) NOT NULL, ADD rehab TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet DROP toGainMuscles, DROP toMaintainWeight, DROP toLoseWeight, DROP toImproveMentalHealth, DROP toImprovePhysicalHealth, DROP fixShoulder, DROP fixKnees, DROP fixBack, DROP rehab');
        $this->addSql('ALTER TABLE routine DROP toGainMuscles, DROP toMaintainWeight, DROP toLoseWeight, DROP toImproveMentalHealth, DROP toImprovePhysicalHealth, DROP fixShoulder, DROP fixKnees, DROP fixBack, DROP rehab');
    }
}
