<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250530141905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet DROP FOREIGN KEY FK_9DE4652061220EA6');
        $this->addSql('ALTER TABLE diet ADD CONSTRAINT FK_9DE4652061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C61220EA6');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D661220EA6');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercise DROP FOREIGN KEY FK_AEDAD51C61220EA6');
        $this->addSql('ALTER TABLE exercise ADD CONSTRAINT FK_AEDAD51C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE diet DROP FOREIGN KEY FK_9DE4652061220EA6');
        $this->addSql('ALTER TABLE diet ADD CONSTRAINT FK_9DE4652061220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D661220EA6');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D661220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
    }
}
