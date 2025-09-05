<?php

namespace App\Entity\Routine;

use App\Entity\User\User;
use App\Repository\Routine\RoutineCategoryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;

#[Entity(repositoryClass: RoutineCategoryRepository::class)]
#[Table(name: "routine_category")]
class RoutineCategory
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

    #[ManyToOne(targetEntity: User::class, inversedBy: 'routineCategory')]
    #[JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: true,  onDelete: "SET NULL")]
    private ?User $user;

    #[OneToMany(mappedBy: "routineCategory", targetEntity: Routine::class, cascade: ["persist", "remove"])]
    private array|Collection $routines;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "name", type: 'string', unique: false, nullable: false)]
    private string $name;

    #[Column(name: "description", type: "string", unique: false, nullable: true)]
    private ?string $description;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", unique: false, nullable: true)]
    protected ?DateTime $updatedAt;

    #[Column(name: "active", type: "boolean", unique: false, nullable: true)]
    protected ?bool $active;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->active = true;

        $this->routines = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return array|Collection
     */
    public function getRoutines(): Collection|array
    {
        return $this->routines;
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
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param string $id
     * @return RoutineCategory
     */
    public function setId(string $id): RoutineCategory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param User|null $user
     * @return RoutineCategory
     */
    public function setUser(?User $user): RoutineCategory
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param array|Collection $routines
     * @return RoutineCategory
     */
    public function setRoutines(Collection|array $routines): RoutineCategory
    {
        $this->routines = $routines;
        return $this;
    }

    /**
     * @param string $name
     * @return RoutineCategory
     */
    public function setName(string $name): RoutineCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $description
     * @return RoutineCategory
     */
    public function setDescription(?string $description): RoutineCategory
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return RoutineCategory
     */
    public function setCreatedAt(DateTime $createdAt): RoutineCategory
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return RoutineCategory
     */
    public function setUpdatedAt(?DateTime $updatedAt): RoutineCategory
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param bool|null $active
     * @return RoutineCategory
     */
    public function setActive(?bool $active): RoutineCategory
    {
        $this->active = $active;
        return $this;
    }
}
