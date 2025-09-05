<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250529181150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        // Agregar permisos 27 y 28
        $this->addSql("INSERT INTO role_has_permission (id, role_id, permission_id) VALUES (UUID(), 3, 27)");
        $this->addSql("INSERT INTO role_has_permission (id, role_id, permission_id) VALUES (UUID(), 3, 28)");
    }

    public function down(Schema $schema): void
    {
        {
            // Eliminar los permisos añadidos
            $this->addSql("DELETE FROM role_has_permission WHERE role_id = 3 AND permission_id IN (27, 28)");
        }
    }
}
