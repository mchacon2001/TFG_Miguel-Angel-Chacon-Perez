<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720152705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE toGainMuscles toGainMuscles TINYINT(1) DEFAULT NULL, CHANGE toLoseWeight toLoseWeight TINYINT(1) DEFAULT NULL, CHANGE fixShoulder fixShoulder TINYINT(1) DEFAULT NULL, CHANGE fixKnees fixKnees TINYINT(1) DEFAULT NULL, CHANGE fixBack fixBack TINYINT(1) DEFAULT NULL, CHANGE rehab rehab TINYINT(1) DEFAULT NULL, CHANGE toMaintainWeight toMaintainWeight TINYINT(1) DEFAULT NULL, CHANGE toImproveMentalHealth toImproveMentalHealth TINYINT(1) DEFAULT NULL, CHANGE toImprovePhysicalHealth toImprovePhysicalHealth TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE toGainMuscles toGainMuscles TINYINT(1) NOT NULL, CHANGE toMaintainWeight toMaintainWeight TINYINT(1) NOT NULL, CHANGE toLoseWeight toLoseWeight TINYINT(1) NOT NULL, CHANGE toImproveMentalHealth toImproveMentalHealth TINYINT(1) NOT NULL, CHANGE toImprovePhysicalHealth toImprovePhysicalHealth TINYINT(1) NOT NULL, CHANGE fixShoulder fixShoulder TINYINT(1) NOT NULL, CHANGE fixKnees fixKnees TINYINT(1) NOT NULL, CHANGE fixBack fixBack TINYINT(1) NOT NULL, CHANGE rehab rehab TINYINT(1) NOT NULL');
    }
}
