<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250711154731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE daily_intake (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, diet_id VARCHAR(255) NOT NULL, food_id VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, meal_type VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2F9CB0D68EA17042 (amount), INDEX IDX_2F9CB0D6A76ED395 (user_id), INDEX IDX_2F9CB0D6E1E13ACE (diet_id), INDEX IDX_2F9CB0D6BA8E87C4 (food_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE daily_intake ADD CONSTRAINT FK_2F9CB0D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE daily_intake ADD CONSTRAINT FK_2F9CB0D6E1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE daily_intake ADD CONSTRAINT FK_2F9CB0D6BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE daily_intake DROP FOREIGN KEY FK_2F9CB0D6A76ED395');
        $this->addSql('ALTER TABLE daily_intake DROP FOREIGN KEY FK_2F9CB0D6E1E13ACE');
        $this->addSql('ALTER TABLE daily_intake DROP FOREIGN KEY FK_2F9CB0D6BA8E87C4');
        $this->addSql('DROP TABLE daily_intake');
    }
}
