<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250605162736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_has_routine (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, routine_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_851C7998A76ED395 (user_id), INDEX IDX_851C7998F27A94C7 (routine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_has_routine ADD CONSTRAINT FK_851C7998A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_routine ADD CONSTRAINT FK_851C7998F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_has_routine DROP FOREIGN KEY FK_851C7998A76ED395');
        $this->addSql('ALTER TABLE user_has_routine DROP FOREIGN KEY FK_851C7998F27A94C7');
        $this->addSql('DROP TABLE user_has_routine');
    }
}
