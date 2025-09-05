<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220211013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO role (id, name, description)
            VALUES
                (1, 'Superadministrador', 'Rol super administrador. Permiso absoluto de la aplicación'),
                (2, 'Administrador', 'Rol de administración. Puede administar los recursos de la aplicación'),
                (3, 'Usuario', 'Rol de usuario. Puede ver los recursos de la aplicación');
        ");

        $this->addSql("INSERT INTO permission_group (id, name, label) 
            VALUES
            (1, 'user', 'Grupo de usuarios'),
            (2, 'roles', 'Grupo de roles'),
            (3, 'routines', 'Grupo de rutinas'),
            (4, 'diets', 'Grupo de dietas'),
            (5, 'user_progress', 'Grupo de progreso del usuario'),
            (6, 'educative_resources', 'Grupo de recursos educativos'),
            (7, 'dashboard', 'Grupo de dashboard')           
            ");

            $this->addSql("INSERT INTO permission (id, permission_group_id, label, action, description, admin_managed, module_dependant) 
            VALUES
            (1, 1, 'Administrar usuarios', 'admin_user', 'Permite administrar los usuarios.', 0, NULL),
            (2, 1, 'Obtener usuario', 'get', 'Permite obtener los usuarios.', 0, NULL),
            (3, 1, 'Listar usuarios', 'list', 'Permite listar los usuarios.', 0, NULL),
            (4, 1, 'Crear usuario', 'create', 'Permite crear los usuarios.', 0, NULL),
            (5, 1, 'Editar usuario', 'edit', 'Permite editar un usuario.', 0, NULL),
            (6, 1, 'Eliminar usuario', 'delete', 'Permite eliminar un usuario.', 0, NULL),
            
            (7, 2, 'Administrar roles', 'admin_roles', 'Permite administrar los roles.', 1, NULL),
            (8, 2, 'Obtener rol', 'get', 'Permite obtener los roles.', 1, NULL),
            (9, 2, 'Listar roles', 'list', 'Permite listar los roles.', 1, NULL),
            (10, 2, 'Crear rol', 'create', 'Permite crear los roles.', 1, NULL),
            (11, 2, 'Editar rol', 'edit', 'Permite editar los roles.', 1, NULL),
            (12, 2, 'Eliminar rol', 'delete', 'Permite eliminar un rol.', 1, NULL),

            (13, 3, 'Administrar rutinas', 'admin_routines', 'Permite administrar las rutinas.', 0, NULL),
            (14, 3, 'Obtener rutina', 'get', 'Permite obtener una rutina.', 0, NULL),
            (15, 3, 'Listar rutinas', 'list', 'Permite listar las rutinas.', 0, NULL),
            (16, 3, 'Crear rutina', 'create', 'Permite crear las rutinas.', 0, NULL),
            (17, 3, 'Editar rutina', 'edit', 'Permite editar una rutina.', 0, NULL),
            (18, 3, 'Eliminar rutina', 'delete', 'Permite eliminar una rutina.', 0, NULL),
            
            (19, 4, 'Administrar dietas', 'admin_diets', 'Permite administrar las dietas.', 0, NULL),
            (20, 4, 'Obtener dieta', 'get', 'Permite obtener las dietas.', 0, NULL),
            (21, 4, 'Listar dietas', 'list', 'Permite listar las dietas.', 0, NULL),
            (22, 4, 'Crear dietas', 'create', 'Permite crear las dietas.', 0, NULL),
            (23, 4, 'Editar dieta', 'edit', 'Permite editar una dieta.', 0, NULL),
            (24, 4, 'Eliminar dieta', 'delete', 'Permite eliminar una dieta.', 0, NULL),

            (25, 5, 'Obtener información del progreso del usuario', 'get_progress', 'Permite obtener toda la información del progreso del usuario', 0, NULL),

            (26, 6, 'Administrar recursos educativos', 'admin_educative_resources', 'Permite administrar los recursos educativos.', 0, NULL),
            (27, 6, 'Obtener recurso educativo', 'get', 'Permite obtener los recursos educativos.', 0, NULL),
            (28, 6, 'Listar recursos educativos', 'list', 'Permite listar los recursos educativos.', 0, NULL),
            (29, 6, 'Crear recurso educativo', 'create', 'Permite crear los recursos educativos.', 0, NULL),
            (30, 6, 'Editar recurso educativo', 'edit', 'Permite editar los recursos educativos.', 0, NULL),
            (31, 6, 'Eliminar recurso educativo', 'delete', 'Permite eliminar un recurso educativo.', 0, NULL),

            (32, 7, 'Obtener información de dashboard', 'get_dashboard', 'Permite obtener toda la información suministrada por el dashboard', 0, NULL)
            ");
            $this->addSql("INSERT INTO role_has_permission (id, role_id, permission_id)
            VALUES
            (uuid(), 1,1),
            (uuid(), 1,2),
            (uuid(), 1,3),
            (uuid(), 1,4),
            (uuid(), 1,5),
            (uuid(), 1,6),
            (uuid(), 1,7),
            (uuid(), 1,8),
            (uuid(), 1,9),
            (uuid(), 1,10),
            (uuid(), 1,11),
            (uuid(), 1,12),
            (uuid(), 1,13),
            (uuid(), 1,14),
            (uuid(), 1,15),
            (uuid(), 1,16),
            (uuid(), 1,17),
            (uuid(), 1,18),
            (uuid(), 1,19),
            (uuid(), 1,20),
            (uuid(), 1,21),
            (uuid(), 1,22),
            (uuid(), 1,23),
            (uuid(), 1,24),
            (uuid(), 1,25),
            (uuid(), 1,26),
            (uuid(), 1,27),
            (uuid(), 1,28),
            (uuid(), 1,29),
            (uuid(), 1,30),
            (uuid(), 1,31),
            (uuid(), 1,32),

            (uuid(), 2,1),
            (uuid(), 2,2),
            (uuid(), 2,3),
            (uuid(), 2,4),
            (uuid(), 2,5),
            (uuid(), 2,6),
            (uuid(), 2,13),
            (uuid(), 2,14),
            (uuid(), 2,15),
            (uuid(), 2,16),
            (uuid(), 2,17),
            (uuid(), 2,18),
            (uuid(), 2,19),
            (uuid(), 2,20),
            (uuid(), 2,21),
            (uuid(), 2,22),
            (uuid(), 2,23),
            (uuid(), 2,24),
            (uuid(), 2,26),
            (uuid(), 2,27),
            (uuid(), 2,28),
            (uuid(), 2,29),
            (uuid(), 2,30),
            (uuid(), 2,31),

            (uuid(), 3,14),
            (uuid(), 3,15),
            (uuid(), 3,16),
            (uuid(), 3,17),
            (uuid(), 3,18),
            (uuid(), 3,20),
            (uuid(), 3,21),
            (uuid(), 3,22),
            (uuid(), 3,23),
            (uuid(), 3,24),
            (uuid(), 3,25),
            (uuid(), 3,32)
            ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM role_has_permission WHERE permission_id BETWEEN 1 AND 32');
        $this->addSql('DELETE FROM permission WHERE id BETWEEN 1 AND 32');
        $this->addSql('DELETE FROM permission_group WHERE id BETWEEN 1 AND 7');

        $this->addSql("DELETE FROM role WHERE id BETWEEN 1 AND 3;");
    }
}
