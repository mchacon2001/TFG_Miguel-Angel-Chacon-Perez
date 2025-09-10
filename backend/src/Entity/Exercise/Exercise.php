<?php

namespace App\Entity\Exercise;

use App\Entity\Routine\RoutineHasExercise;
use App\Entity\User\User;
use App\Repository\Exercise\ExerciseRepository;
use DateTime;
use App\Entity\Routine\RoutineRegisterExercises;
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


#[Entity(repositoryClass: ExerciseRepository::class)]
#[Table(name: "exercise")]
class Exercise
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

    #[ManyToOne(targetEntity: User::class, inversedBy: 'exercises')]
    #[JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: true,  onDelete: "CASCADE")]
    private ?User $user;

    #[ManyToOne(targetEntity: ExerciseCategory::class, inversedBy: 'exercises')]
    #[JoinColumn(name: 'exercise_category_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private ExerciseCategory $exerciseCategories;

    #[OneToMany(mappedBy: "exercise", targetEntity: RoutineHasExercise::class, cascade: ["persist", "remove"])]
    private array|Collection $routineHasExercise;

    #[OneToMany(mappedBy: "exercise", targetEntity: RoutineRegisterExercises::class, cascade: ["persist", "remove"])]
    private array|Collection $routineRegisterExercises;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------
    #[Column(name: "name", type: 'string', unique: false, nullable: false)]
    private string $name;

    #[Column(name: "description", type: 'text', unique: false, nullable: true)]
    private ?string $description;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", unique: false, nullable: true)]
    protected ?DateTime $updatedAt;

    #[Column(name: "active", type: "boolean", unique: false, nullable: false)]
    private bool $active;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->active    = true;

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
     * @return ExerciseCategory
     */
    public function getExerciseCategories(): ExerciseCategory
    {
        return $this->exerciseCategories;
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
     * @return array|Collection
     */
    public function getRoutineHasExercise(): Collection|array
    {
        return $this->routineHasExercise;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param string $id
     * @return Exercise
     */
    public function setId(string $id): Exercise
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param User|null $user
     * @return Exercise
     */
    public function setUser(?User $user): Exercise
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param ExerciseCategory $exerciseCategories
     * @return Exercise
     */
    public function setExerciseCategories(ExerciseCategory $exerciseCategories): Exercise
    {
        $this->exerciseCategories = $exerciseCategories;
        return $this;
    }

    /**
     * @param string $name
     * @return Exercise
     */
    public function setName(string $name): Exercise
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $description
     * @return Exercise
     */
    public function setDescription(?string $description): Exercise
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return Exercise
     */
    public function setCreatedAt(DateTime $createdAt): Exercise
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return Exercise
     */
    public function setUpdatedAt(?DateTime $updatedAt): Exercise
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param bool $active
     * @return Exercise
     */
    public function setActive(bool $active): Exercise
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @param array|Collection $routineHasExercise
     * @return Exercise
     */
    public function setRoutineHasExercise(Collection|array $routineHasExercise): Exercise
    {
        $this->routineHasExercise = $routineHasExercise;
        return $this;
    }

}
