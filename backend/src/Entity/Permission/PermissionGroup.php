<?php

namespace App\Entity\Permission;

use App\Entity\User\UserHasRole;
use App\Repository\Permission\PermissionGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\Table;


#[Entity(repositoryClass: PermissionGroupRepository::class)]
#[Table(name: "permission_group")]
class PermissionGroup
{

    // ----------------------------------------------------------------
    // Primary Key
    // ----------------------------------------------------------------

    #[Id]
    #[Column(name: 'id', type: "integer")]
    #[GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    #[OneToMany(mappedBy: "permissionGroup", targetEntity: Permission::class, cascade: ["persist"])]
    #[OrderBy(["action" => "ASC"])]
    protected Collection $permissions;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "name", type: "string", unique: false, nullable: false)]
    protected string $name;

    #[Column(name: "label", type: "string", unique: false, nullable: false)]
    protected string $label;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
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
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param int $id
     * @return PermissionGroup
     */
    public function setId(int $id): PermissionGroup
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param ArrayCollection|Collection $permissions
     * @return PermissionGroup
     */
    public function setPermissions(ArrayCollection|Collection $permissions): PermissionGroup
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @param string $name
     * @return PermissionGroup
     */
    public function setName(string $name): PermissionGroup
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $label
     * @return PermissionGroup
     */
    public function setLabel(string $label): PermissionGroup
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param Permission $permission
     * @return PermissionGroup
     */
    public function addPermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission))
            $this->permissions->add($permission);

        return $this;
    }

    /**
     * @param Permission $permission
     * @return PermissionGroup
     */
    public function removePermission(Permission $permission): self
    {
        if (!$this->permissions->contains($permission))
            $this->permissions->removeElement($permission);

        return $this;
    }
}
