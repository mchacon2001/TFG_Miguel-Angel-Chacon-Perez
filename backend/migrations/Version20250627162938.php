<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627162938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_has_diet (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, diet_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_20355A7FA76ED395 (user_id), INDEX IDX_20355A7FE1E13ACE (diet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_has_diet ADD CONSTRAINT FK_20355A7FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_diet ADD CONSTRAINT FK_20355A7FE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_has_diet DROP FOREIGN KEY FK_20355A7FA76ED395');
        $this->addSql('ALTER TABLE user_has_diet DROP FOREIGN KEY FK_20355A7FE1E13ACE');
        $this->addSql('DROP TABLE user_has_diet');
    }
}
