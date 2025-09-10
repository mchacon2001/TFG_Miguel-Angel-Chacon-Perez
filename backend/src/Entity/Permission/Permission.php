<?php

namespace App\Entity\Permission;

use App\Entity\User\RoleHasPermission;
use App\Entity\User\UserHasPermission;
use App\Repository\Permission\PermissionRepository;
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

#[Entity(repositoryClass: PermissionRepository::class)]
#[Table(name: "permission")]
class Permission
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

    #[ManyToOne(targetEntity: PermissionGroup::class, inversedBy: 'permissions', fetch: 'EAGER')]
    #[JoinColumn(name: 'permission_group_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    protected PermissionGroup $permissionGroup;

    #[OneToMany(mappedBy: "permission", targetEntity: UserHasPermission::class, cascade: ["persist"])]
    protected Collection $permissionUsers;

    #[OneToMany(mappedBy: "permission", targetEntity: RoleHasPermission::class, cascade: ["persist"])]
    protected Collection $permissionRoles;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "action", type: "string", unique: false, nullable: false)]
    protected string $action;

    #[Column(name: "label", type: "string", unique: false, nullable: false)]
    protected string $label;

    #[Column(name: "description", type: "text", unique: false, nullable: true)]
    protected ?string $description;

    #[Column(type: 'boolean', length: 180, nullable: false, options:['default' => false])]
    private bool $adminManaged = false;

    #[Column(name:"module_dependant", type: 'string', length: 200, nullable: true)]
    private ?string $moduleDependant = null;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->permissionUsers = new ArrayCollection();
        $this->permissionRoles = new ArrayCollection();
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
     * @return PermissionGroup
     */
    public function getPermissionGroup(): PermissionGroup
    {
        return $this->permissionGroup;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPermissionUsers(): ArrayCollection|Collection
    {
        return $this->permissionUsers;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPermissionRoles(): ArrayCollection|Collection
    {
        return $this->permissionRoles;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
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
    public function isAdminManaged(): bool
    {
        return $this->adminManaged;
    }

    /**
     * @return string|null
     */
    public function getModuleDependant(): ?string
    {
        return $this->moduleDependant;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param int $id
     * @return Permission
     */
    public function setId(int $id): Permission
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param PermissionGroup $permissionGroup
     * @return Permission
     */
    public function setPermissionGroup(PermissionGroup $permissionGroup): Permission
    {
        $this->permissionGroup = $permissionGroup;
        return $this;
    }

    /**
     * @param ArrayCollection|Collection $permissionUsers
     * @return Permission
     */
    public function setPermissionUsers(ArrayCollection|Collection $permissionUsers): Permission
    {
        $this->permissionUsers = $permissionUsers;
        return $this;
    }

    /**
     * @param ArrayCollection|Collection $permissionRoles
     * @return Permission
     */
    public function setPermissionRoles(ArrayCollection|Collection $permissionRoles): Permission
    {
        $this->permissionRoles = $permissionRoles;
        return $this;
    }

    /**
     * @param string $action
     * @return Permission
     */
    public function setAction(string $action): Permission
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param string $label
     * @return Permission
     */
    public function setLabel(string $label): Permission
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string|null $description
     * @return Permission
     */
    public function setDescription(?string $description): Permission
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param bool $adminManaged
     * @return Permission
     */
    public function setAdminManaged(bool $adminManaged): Permission
    {
        $this->adminManaged = $adminManaged;
        return $this;
    }

    /**
     * @param string|null $moduleDependant
     * @return Permission
     */
    public function setModuleDependant(?string $moduleDependant): Permission
    {
        $this->moduleDependant = $moduleDependant;
        return $this;
    }
}
