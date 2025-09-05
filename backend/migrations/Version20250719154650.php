<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719154650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE daily_intake DROP FOREIGN KEY FK_2F9CB0D6E1E13ACE');
        $this->addSql('DROP INDEX IDX_2F9CB0D6E1E13ACE ON daily_intake');
        $this->addSql('ALTER TABLE daily_intake DROP diet_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE daily_intake ADD diet_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE daily_intake ADD CONSTRAINT FK_2F9CB0D6E1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2F9CB0D6E1E13ACE ON daily_intake (diet_id)');
    }
}
