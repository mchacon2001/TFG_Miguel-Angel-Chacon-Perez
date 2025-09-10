<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628004808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet DROP FOREIGN KEY FK_9DE465202F0B9B80');
        $this->addSql('DROP INDEX IDX_9DE465202F0B9B80 ON diet');
        $this->addSql('ALTER TABLE diet DROP user_has_diet_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diet ADD user_has_diet_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE diet ADD CONSTRAINT FK_9DE465202F0B9B80 FOREIGN KEY (user_has_diet_id) REFERENCES user_has_diet (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9DE465202F0B9B80 ON diet (user_has_diet_id)');
    }
}
