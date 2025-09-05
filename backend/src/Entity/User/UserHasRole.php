<?php


namespace App\Entity\User;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use App\Repository\User\UserHasRoleRepository;

#[Entity(repositoryClass: UserHasRoleRepository::class)]
#[Table(name: "user_has_role", uniqueConstraints: [
    new UniqueConstraint(name: "user_has_role_unique", columns: ["user_id", "role_id"])
])]
class UserHasRole
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

    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'userRoles',  fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Role::class, cascade:["persist"], inversedBy: 'userRole', fetch: 'EAGER')]
    #[JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Role $role;


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
     * @return Role
     */
    public function getRole(): Role
    {

        return $this->role;
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
     * @return UserHasRole
     */
    public function setUser(User $user): UserHasRole
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Role $role
     * @return UserHasRole
     */
    public function setRole(Role $role): UserHasRole
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param string $id
     * @return UserHasRole
     */
    public function setId(string $id): UserHasRole
    {
        $this->id = $id;
        return $this;
    }

    // ----------------------------------------------------------------
}
