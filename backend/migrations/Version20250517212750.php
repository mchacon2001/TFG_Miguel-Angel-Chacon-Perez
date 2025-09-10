<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517212750 extends AbstractMigration
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
            (9, 'food', 'Grupo de alimentos')
            ");
        $this->addSql("INSERT INTO permission (id, permission_group_id, label, action, description, admin_managed, module_dependant)
        VALUES
        (39, 9, 'Administrar alimentos', 'admin_food', 'Permite administrar los alimentos.', 0, NULL),
        (40, 9, 'Obtener alimento', 'get', 'Permite obtener los alimentos.', 0, NULL),
        (41, 9, 'Listar alimentos', 'list', 'Permite listar los alimentos.', 0, NULL),
        (42, 9, 'Crear alimento', 'create', 'Permite crear un alimento.', 0, NULL),
        (43, 9, 'Editar alimento', 'edit', 'Permite editar un alimento.', 0, NULL),
        (44, 9, 'Eliminar alimento', 'delete', 'Permite eliminar un alimento.', 0, NULL)
        ");
        $this->addSql("INSERT INTO role_has_permission (id, role_id, permission_id)
        VALUES
        (uuid(), 1,39),
        (uuid(), 1,40),
        (uuid(), 1,41),
        (uuid(), 1,42),
        (uuid(), 1,43),
        (uuid(), 1,44),

        (uuid(), 2,39),
        (uuid(), 2,40),
        (uuid(), 2,41),
        (uuid(), 2,42),
        (uuid(), 2,43),
        (uuid(), 2,44),
        
        (uuid(), 3,40),
        (uuid(), 3,41),
        (uuid(), 3,42),
        (uuid(), 3,43),
        (uuid(), 3,44)
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM role_has_permission WHERE permission_id BETWEEN 39 AND 44");
        $this->addSql("DELETE FROM permission WHERE id BETWEEN 39 AND 44");
        $this->addSql("DELETE FROM permission_group WHERE id = 9");
    }
}
