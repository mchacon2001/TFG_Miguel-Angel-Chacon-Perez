<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603155904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(" INSERT INTO role_has_permission (id, role_id, permission_id)
        VALUES 
        (uuid(), 3, 5)
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql(" DELETE FROM role_has_permission WHERE role_id = 3 AND permission_id = 2
        ");
    }
}
