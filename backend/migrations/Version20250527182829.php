<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527182829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD target_weight DOUBLE PRECISION NOT NULL, ADD sex VARCHAR(255) NOT NULL, ADD birthdate DATETIME NOT NULL, DROP last_name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649EC7B2F1 ON user (target_weight)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649EFA269F7 ON user (sex)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649EC7B2F1 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649EFA269F7 ON user');
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(255) DEFAULT NULL, DROP target_weight, DROP sex, DROP birthdate');
    }
}
