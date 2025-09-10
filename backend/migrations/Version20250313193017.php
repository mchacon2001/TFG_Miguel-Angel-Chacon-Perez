<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313193017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO permission_group (id, name, label) 
            VALUES
            (8, 'exercises', 'Grupo de ejercicios')
            ");

        $this->addSql("INSERT INTO permission (id, permission_group_id, label, action, description, admin_managed, module_dependant) 
        VALUES
        (33, 8, 'Administrar ejercicios', 'admin_exercises', 'Permite administrar los ejercicios.', 0, NULL),
        (34, 8, 'Obtener ejercicio', 'get', 'Permite obtener los ejercicios.', 0, NULL),
        (35, 8, 'Listar ejercicios', 'list', 'Permite listar los ejercicios.', 0, NULL),
        (36, 8, 'Crear ejercicio', 'create', 'Permite crear un ejercicio.', 0, NULL),
        (37, 8, 'Editar ejercicio', 'edit', 'Permite editar un ejercicio.', 0, NULL),
        (38, 8, 'Eliminar ejercicio', 'delete', 'Permite eliminar un ejercicio.', 0, NULL)
        ");

        $this->addSql("INSERT INTO role_has_permission (id, role_id, permission_id)
        VALUES
        (uuid(), 1,33),
        (uuid(), 1,34),
        (uuid(), 1,35),
        (uuid(), 1,36),
        (uuid(), 1,37),
        (uuid(), 1,38),

        (uuid(), 2,33),
        (uuid(), 2,34),
        (uuid(), 2,35),
        (uuid(), 2,36),
        (uuid(), 2,37),
        (uuid(), 2,38),

        (uuid(), 3,34),
        (uuid(), 3,35),
        (uuid(), 3,36),
        (uuid(), 3,37),
        (uuid(), 3,38)
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM role_has_permission WHERE permission_id BETWEEN 33 AND 36');
        $this->addSql('DELETE FROM permission WHERE id BETWEEN 33 AND 36');
        $this->addSql('DELETE FROM permission_group WHERE id = 8');
    }
}
