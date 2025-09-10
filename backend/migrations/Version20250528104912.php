<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528104912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_has_mental_stats (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, mood INT NOT NULL, sleepQuality INT NOT NULL, recordedAt DATETIME NOT NULL, INDEX IDX_5138AC36A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_has_physical_stats (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, height INT NOT NULL, body_fat DOUBLE PRECISION NOT NULL, bmi DOUBLE PRECISION NOT NULL, recordedAt DATETIME NOT NULL, INDEX IDX_F773F8F8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_has_mental_stats ADD CONSTRAINT FK_5138AC36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_physical_stats ADD CONSTRAINT FK_F773F8F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_has_mental_stats DROP FOREIGN KEY FK_5138AC36A76ED395');
        $this->addSql('ALTER TABLE user_has_physical_stats DROP FOREIGN KEY FK_F773F8F8A76ED395');
        $this->addSql('DROP TABLE user_has_mental_stats');
        $this->addSql('DROP TABLE user_has_physical_stats');
    }
}
