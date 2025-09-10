<?php


namespace App\Entity\User;


use App\Entity\Permission\Permission;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: "role_has_permission", uniqueConstraints: [
    new UniqueConstraint(name: "role_permission_unique", columns: ["role_id", "permission_id"])
])]
class RoleHasPermission
{

    // ----------------------------------------------------------------
    // Primary Key
    // ----------------------------------------------------------------

    #[Id]
    #[Column(name: "id", type: "string", unique: true)]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected string $id;

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    #[ManyToOne(targetEntity: Role::class, inversedBy: 'permissions' )]
    #[JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Role $role;

    #[ManyToOne(targetEntity: Permission::class, inversedBy: 'permissionRoles', fetch: 'EAGER')]
    #[JoinColumn(name: 'permission_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Permission $permission;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------



    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------



    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return Permission
     */
    public function getPermission(): Permission
    {
        return $this->permission;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param Role $role
     * @return RoleHasPermission
     */
    public function setRole(Role $role): RoleHasPermission
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param Permission $permission
     * @return RoleHasPermission
     */
    public function setPermission(Permission $permission): RoleHasPermission
    {
        $this->permission = $permission;
        return $this;
    }

    // ----------------------------------------------------------------
    // Extra Methods
    // ----------------------------------------------------------------
}
