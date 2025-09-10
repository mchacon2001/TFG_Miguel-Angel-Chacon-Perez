<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529191100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("DELETE FROM role_has_permission WHERE role_id = 3 AND permission_id IN (42, 43, 44)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("INSERT INTO role_has_permission (role_id, permission_id) VALUES (3, 42), (3, 43), (3, 44)");

    }
}
