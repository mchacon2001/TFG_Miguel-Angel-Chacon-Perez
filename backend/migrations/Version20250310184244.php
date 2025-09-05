<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310184244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercise (id VARCHAR(255) NOT NULL, creator_id VARCHAR(255) DEFAULT NULL, exercise_category_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_AEDAD51C61220EA6 (creator_id), INDEX IDX_AEDAD51C5FB48D66 (exercise_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise_category (id VARCHAR(255) NOT NULL, creator_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_20B92961220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine (id VARCHAR(255) NOT NULL, creator_id VARCHAR(255) DEFAULT NULL, routine_category_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_4BF6D8D661220EA6 (creator_id), INDEX IDX_4BF6D8D6B73D7EA (routine_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine_category (id VARCHAR(255) NOT NULL, creator_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT NULL, INDEX IDX_F858FCF761220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine_has_exercise (id VARCHAR(255) NOT NULL, exercise_id VARCHAR(255) NOT NULL, routine_id VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, sets INT NOT NULL, reps INT NOT NULL, rest_time INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B17E9250E934951A (exercise_id), INDEX IDX_B17E9250F27A94C7 (routine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C5FB48D66 FOREIGN KEY (exercise_category_id) REFERENCES exercise_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise_category ADD CONSTRAINT FK_20B92961220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D6B73D7EA FOREIGN KEY (routine_category_id) REFERENCES routine_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine_category ADD CONSTRAINT FK_F858FCF761220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE routine_has_exercise ADD CONSTRAINT FK_B17E9250E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine_has_exercise ADD CONSTRAINT FK_B17E9250F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C61220EA6');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C5FB48D66');
        $this->addSql('ALTER TABLE exercise_category DROP FOREIGN KEY FK_20B92961220EA6');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D661220EA6');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D6B73D7EA');
        $this->addSql('ALTER TABLE routine_category DROP FOREIGN KEY FK_F858FCF761220EA6');
        $this->addSql('ALTER TABLE routine_has_exercise DROP FOREIGN KEY FK_B17E9250E934951A');
        $this->addSql('ALTER TABLE routine_has_exercise DROP FOREIGN KEY FK_B17E9250F27A94C7');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE exercise_category');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE routine_category');
        $this->addSql('DROP TABLE routine_has_exercise');
    }
}
