<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614195047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE routine_register (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, routine_id VARCHAR(255) NOT NULL, start_time INT NOT NULL, end_time INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A1EDA522A76ED395 (user_id), INDEX IDX_A1EDA522F27A94C7 (routine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine_register_exercises (id VARCHAR(255) NOT NULL, exercise_id VARCHAR(255) NOT NULL, reps INT NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, routineRegister_id VARCHAR(255) NOT NULL, INDEX IDX_5EAF14A8F5DDCD47 (routineRegister_id), INDEX IDX_5EAF14A8E934951A (exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE routine_register ADD CONSTRAINT FK_A1EDA522A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine_register ADD CONSTRAINT FK_A1EDA522F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine_register_exercises ADD CONSTRAINT FK_5EAF14A8F5DDCD47 FOREIGN KEY (routineRegister_id) REFERENCES routine_register (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine_register_exercises ADD CONSTRAINT FK_5EAF14A8E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE routine_register DROP FOREIGN KEY FK_A1EDA522A76ED395');
        $this->addSql('ALTER TABLE routine_register DROP FOREIGN KEY FK_A1EDA522F27A94C7');
        $this->addSql('ALTER TABLE routine_register_exercises DROP FOREIGN KEY FK_5EAF14A8F5DDCD47');
        $this->addSql('ALTER TABLE routine_register_exercises DROP FOREIGN KEY FK_5EAF14A8E934951A');
        $this->addSql('DROP TABLE routine_register');
        $this->addSql('DROP TABLE routine_register_exercises');
    }
}
