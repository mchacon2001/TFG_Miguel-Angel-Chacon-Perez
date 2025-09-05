<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517211743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diet (id VARCHAR(255) NOT NULL, creator_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, goal VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9DE4652061220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diet_has_food (id VARCHAR(255) NOT NULL, diet_id VARCHAR(255) NOT NULL, food_id VARCHAR(255) NOT NULL, day_of_week VARCHAR(20) NOT NULL, meal_type VARCHAR(20) NOT NULL, amount DOUBLE PRECISION NOT NULL, notes VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_EB491C2DE1E13ACE (diet_id), INDEX IDX_EB491C2DBA8E87C4 (food_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food (id VARCHAR(255) NOT NULL, creator_id VARCHAR(255) DEFAULT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, calories DOUBLE PRECISION DEFAULT NULL, proteins DOUBLE PRECISION DEFAULT NULL, carbs DOUBLE PRECISION DEFAULT NULL, fats DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, INDEX IDX_D43829F761220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE diet ADD CONSTRAINT FK_9DE4652061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE diet_has_food ADD CONSTRAINT FK_EB491C2DE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE diet_has_food ADD CONSTRAINT FK_EB491C2DBA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id)');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F761220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet DROP FOREIGN KEY FK_9DE4652061220EA6');
        $this->addSql('ALTER TABLE diet_has_food DROP FOREIGN KEY FK_EB491C2DE1E13ACE');
        $this->addSql('ALTER TABLE diet_has_food DROP FOREIGN KEY FK_EB491C2DBA8E87C4');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F761220EA6');
        $this->addSql('DROP TABLE diet');
        $this->addSql('DROP TABLE diet_has_food');
        $this->addSql('DROP TABLE food');
    }
}
