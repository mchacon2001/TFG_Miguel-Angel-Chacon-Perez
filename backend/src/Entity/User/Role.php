<?php

namespace App\Entity\User;

use App\Repository\User\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: RoleRepository::class)]
#[Table(name: "role")]
class Role
{
    public const ROLE_SUPER_ADMIN = 1;
    public const ROLE_ADMIN = 2;
    public const ROLE_USER = 3;

    // ----------------------------------------------------------------
    // Primary Key
    // ----------------------------------------------------------------

    #[Id]
    #[Column(name: 'id', type: "integer")]
    #[GeneratedValue(strategy: 'AUTO')]
    private ?int $id;

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    #[OneToMany(mappedBy: "role", targetEntity: UserHasRole::class, cascade: ["persist"])]
    private Collection $userRole;

    #[OneToMany(mappedBy: "role", targetEntity: RoleHasPermission::class, cascade: ["persist"])]
    private Collection $permissions;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "name", type: "string", unique: false, nullable: false)]
    #[Assert\NotBlank(message: "El nombre no puede estar vacío.")]
    private string $name;

    #[Column(name: "description", type: "text", unique: false, nullable: true)]
    private ?string $description;

    #[Column(name: "active", type: "boolean", unique: false, nullable: false, options: ["default" => "1"])]
    private bool $active;

    #[Column(name: "immutable", type: "boolean", unique: false, nullable: false, options: ["default" => "0"])]
    private bool $immutable;


    // ----------------------------------------------------------------
    // Methods
    // ----------------------------------------------------------------

    public function __construct()
    {

        $this->users       = new ArrayCollection();
        $this->permissions = new ArrayCollection();

        $this->active = true;
        $this->immutable = false;
    }

    public function __clone()
    {
        $this->setId(null);
        $permissions = new ArrayCollection();
        foreach ($this->permissions as $permission)
        {
            /* @var RoleHasPermission $permissionClone */
            $permissionClone = clone $permission;
            $permissionClone->setRole($this);
            $permissions->add($permissionClone);
        }
        $this->permissions = $permissions;

    }

    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getUserRole(): ArrayCollection|Collection
    {
        return $this->userRole;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPermissions(): ArrayCollection|Collection
    {
        return $this->permissions;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isImmutable(): bool
    {
        return $this->immutable;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param int|null $id
     * @return Role
     */
    public function setId(?int $id): Role
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param ArrayCollection|Collection $userRole
     * @return Role
     */
    public function setUserRole(ArrayCollection|Collection $userRole): Role
    {
        $this->userRole = $userRole;
        return $this;
    }

    /**
     * @param ArrayCollection|Collection $permissions
     * @return Role
     */
    public function setPermissions(ArrayCollection|Collection $permissions): Role
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @param string $name
     * @return Role
     */
    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $description
     * @return Role
     */
    public function setDescription(?string $description): Role
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param RoleHasPermission $roleHasPermission
     * @return Role
     */
    public function addPermission(RoleHasPermission $roleHasPermission): self
    {
        if (!$this->permissions->contains($roleHasPermission)) {
            $roleHasPermission->setRole($this);
            $this->permissions->add($roleHasPermission);
        }

        return $this;
    }


    public function removePermission(RoleHasPermission $roleHasPermission): self
    {
        if ($this->permissions->contains($roleHasPermission))
            $this->permissions->removeElement($roleHasPermission);
        return $this;
    }

    /**
     * @param bool $active
     * @return Role
     */
    public function setActive(bool $active): Role
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @param bool $immutable
     * @return Role
     */
    public function setImmutable(bool $immutable): Role
    {
        $this->immutable = $immutable;
        return $this;
    }

    // ----------------------------------------------------------------
    // Extra Methods
    // ----------------------------------------------------------------

    public function getPermissionsArray(): array
    {
        $result = [];
        /** @var RoleHasPermission $permission */
        foreach ($this->permissions as $permission){
            $result[] = $permission->getPermission();
        }

        return $result;
    }

    public function getPermissionsSchema():array
    {
        $permissions = [];

        /** @var UserHasPermission $userPermission */
        foreach ($this->getPermissions() as $userPermission) {

            $permission    = $userPermission->getPermission();
            $group = $permission->getPermissionGroup();

            if(!isset($permissions[$group->getName()])){    
                $permissions[$group->getName()] = [];
            }
            $permissions[$group->getName()][] = $permission->getAction();

        }

        return $permissions;
    }

}
