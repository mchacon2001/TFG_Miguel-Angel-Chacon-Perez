<?php

namespace App\Entity\Routine;

use App\Entity\User\User;
use App\Entity\User\UserHasRoutine;
use App\Repository\Routine\RoutineRepository;
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
use Doctrine\ORM\PersistentCollection;


#[Entity(repositoryClass: RoutineRepository::class)]
#[Table(name: "routine")]
class Routine
{

    const UPLOAD_FILES_PATH = 'routines';

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

    #[ManyToOne(targetEntity: User::class, inversedBy: 'routines')]
    #[JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: true,  onDelete: "CASCADE")]
    private ?User $user;

    #[ManyToOne(targetEntity: RoutineCategory::class, inversedBy: 'routines')]
    #[JoinColumn(name: 'routine_category_id', referencedColumnName: 'id', nullable: true,  onDelete: "CASCADE")]
    private RoutineCategory $routineCategory;

    #[OneToMany(mappedBy: "routines", targetEntity: RoutineHasExercise::class, cascade: ["persist"])]
    private array|Collection $routineHasExercise;

    #[OneToMany(mappedBy: "routine", targetEntity: UserHasRoutine::class, cascade: ["persist"])]
    private array|Collection $userRoutines;

    #[OneToMany(mappedBy: "routines", targetEntity: RoutineRegister::class, cascade: ["persist"])]
    private array|Collection $routineRegister;

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

    #[Column(name: "active", type: "boolean", unique: false, nullable: false)]
    protected bool $active;

    #[Column(name: "quantity", type: "integer", nullable: false, options: ["default" => 0])]
    private int $quantity = 0;

    #[Column(name: "toGainMuscles", type: "boolean", unique: false, nullable: false)]
    private bool $toGainMuscle;

    #[Column(name: "toMaintainWeight", type: "boolean", unique: false, nullable: false)]
    private bool $toMaintainWeight;

    #[Column(name: "toLoseWeight", type: "boolean", unique: false, nullable: false)]
    private bool $toLoseWeight;

    #[Column(name: "toImproveMentalHealth", type: "boolean", unique: false, nullable: false)]
    private bool $toImproveMentalHealth;

    #[Column(name: "toImprovePhysicalHealth", type: "boolean", unique: false, nullable: false)]
    private bool $toImprovePhysicalHealth;

    #[Column(name: "fixShoulder", type: "boolean", unique: false, nullable: false)]
    private bool $fixShoulder;

    #[Column(name: "fixKnees", type: "boolean", unique: false, nullable: false)]
    private bool $fixKnees;

    #[Column(name: "fixBack", type: "boolean", unique: false, nullable: false)]
    private bool $fixBack;

    #[Column(name: "rehab", type: "boolean", unique: false, nullable: false)]
    private bool $rehab;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->active = true;

        $this->routineHasExercise = new ArrayCollection();
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
     * @return RoutineCategory
     */
    public function getRoutineCategory(): RoutineCategory
    {
        return $this->routineCategory;
    }

    /**
     * @return array|Collection
     */
    public function getRoutineHasExercise(): Collection|array
    {
        return $this->routineHasExercise;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return bool
     */
    public function isToGainMuscle(): bool
    {
        return $this->toGainMuscle;
    }
    /**
     * @return bool
     */
    public function isToMaintainWeight(): bool
    {
        return $this->toMaintainWeight;
    }
    /**
     * @return bool
     */
    public function isToLoseWeight(): bool
    {
        return $this->toLoseWeight;
    }
    /**
     * @return bool
     */
    public function isToImproveMentalHealth(): bool
    {
        return $this->toImproveMentalHealth;
    }
    /**
     * @return bool
     */
    public function isToImprovePhysicalHealth(): bool
    {
        return $this->toImprovePhysicalHealth;
    }
    /**
     * @return bool
     */
    public function isFixShoulder(): bool
    {
        return $this->fixShoulder;
    }
    /**
     * @return bool
     */
    public function isFixKnees(): bool
    {
        return $this->fixKnees;
    }
    /**
     * @return bool
     */
    public function isFixBack(): bool
    {
        return $this->fixBack;
    }
    /**
     * @return bool
     */
    public function isRehab(): bool
    {
        return $this->rehab;
    }

    /**
     * @return array|Collection
     */
    public function getUserRoutines(): Collection|array
    {
        return $this->userRoutines;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param string $id
     * @return Routine
     */
    public function setId(string $id): Routine
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param User|null $user
     * @return Routine
     */
    public function setUser(?User $user): Routine
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param RoutineCategory $RoutineCategory
     * @return Routine
     */
    public function setRoutineCategory(RoutineCategory $routineCategory): Routine
    {
        $this->routineCategory = $routineCategory;
        return $this;
    }

    /**
     * @param array|Collection $routineHasExercise
     * @return Routine
     */
    public function setRoutineHasExercise(Collection|array $routineHasExercise): Routine
    {
        $this->routineHasExercise = $routineHasExercise;
        return $this;
    }

    /**
     * @param string $name
     * @return Routine
     */
    public function setName(string $name): Routine
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $description
     * @return Routine
     */
    public function setDescription(?string $description): Routine
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return Routine
     */
    public function setCreatedAt(DateTime $createdAt): Routine
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return Routine
     */
    public function setUpdatedAt(?DateTime $updatedAt): Routine
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param bool $active
     * @return Routine
     */
    public function setActive(bool $active): Routine
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @param int $quantity
     * @return Routine
     */
    public function setQuantity(int $quantity): Routine
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param bool $toGainMuscle
     * @return Routine
     */
    public function setToGainMuscle(bool $toGainMuscle): Routine
    {
        $this->toGainMuscle = $toGainMuscle;
        return $this;
    }
    /**
     * @param bool $toMaintainWeight
     * @return Routine
     */
    public function setToMaintainWeight(bool $toMaintainWeight): Routine
    {
        $this->toMaintainWeight = $toMaintainWeight;
        return $this;
    }
    /**
     * @param bool $toLoseWeight
     * @return Routine
     */
    public function setToLoseWeight(bool $toLoseWeight): Routine
    {
        $this->toLoseWeight = $toLoseWeight;
        return $this;
    }
    /**
     * @param bool $toImproveMentalHealth
     * @return Routine
     */
    public function setToImproveMentalHealth(bool $toImproveMentalHealth): Routine
    {
        $this->toImproveMentalHealth = $toImproveMentalHealth;
        return $this;
    }
    /**
     * @param bool $toImprovePhysicalHealth
     * @return Routine
     */
    public function setToImprovePhysicalHealth(bool $toImprovePhysicalHealth): Routine
    {
        $this->toImprovePhysicalHealth = $toImprovePhysicalHealth;
        return $this;
    }
    /**
     * @param bool $fixShoulder
     * @return Routine
     */
    public function setFixShoulder(bool $fixShoulder): Routine
    {
        $this->fixShoulder = $fixShoulder;
        return $this;
    }
    /**
     * @param bool $fixKnees
     * @return Routine
     */
    public function setFixKnees(bool $fixKnees): Routine
    {
        $this->fixKnees = $fixKnees;
        return $this;
    }
    /**
     * @param bool $fixBack
     * @return Routine
     */
    public function setFixBack(bool $fixBack): Routine
    {
        $this->fixBack = $fixBack;
        return $this;
    }
    /**
     * @param bool $rehab
     * @return Routine
     */
    public function setRehab(bool $rehab): Routine
    {
        $this->rehab = $rehab;
        return $this;
    }

    /**
     * @param array|Collection $userRoutines
     * @return Routine
     */
    public function setUserRoutines(Collection|array $userRoutines): Routine
    {
        $this->userRoutines = $userRoutines;
        return $this;
    }
    // ----------------------------------------------------------------
}
