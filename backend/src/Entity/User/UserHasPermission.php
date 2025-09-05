<?php


namespace App\Entity\User;

use App\Entity\Permission\Permission;
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
#[Table(name: "user_has_permission", uniqueConstraints: [
    new UniqueConstraint(name: "user_has_permission_unique_relation", columns: ["user_id", "permission_id"])
])]
class UserHasPermission
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


    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'permissions')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Permission::class, cascade:["persist"], inversedBy: 'permissionUsers', fetch: 'EAGER')]
    #[JoinColumn(name: 'permission_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Permission $permission;


    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Permission
     */
    public function getPermission(): Permission
    {
        return $this->permission;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }



    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param User $user
     * @return UserHasPermission
     */
    public function setUser(User $user): UserHasPermission
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Permission $permission
     * @return UserHasPermission
     */
    public function setPermission(Permission $permission): UserHasPermission
    {
        $this->permission = $permission;
        return $this;
    }

    /**
     * @param string $id
     * @return UserHasPermission
     */
    public function setId(string $id): UserHasPermission
    {
        $this->id = $id;
        return $this;
    }
}
