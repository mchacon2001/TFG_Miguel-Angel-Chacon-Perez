<?php

namespace App\Entity\Exercise;

use App\Entity\User\User;
use App\Repository\Exercise\ExerciseCategoryRepository;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\Common\Collections\ArrayCollection;


#[Entity(repositoryClass: ExerciseCategoryRepository::class)]
#[Table(name: "exercise_category")]
class ExerciseCategory
{

    // Constants declaration
    const UPLOAD_FILES_PATH = 'exerciseCategories'; 

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


    #[ManyToOne(targetEntity: User::class, inversedBy: 'exerciseCategories')]
    #[JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: true,  onDelete: "SET NULL")]
    private ?User $user;

    #[OneToMany(mappedBy: "exerciseCategories", targetEntity: Exercise::class, cascade: ["persist", "remove"])]
    private array|Collection $exercises;


    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "name", type: 'string', unique: false, nullable: false)]
    private string $name;

    #[Column(name: "description", type: 'string', unique: false, nullable: true)]
    private ?string $description;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", unique: false, nullable: true)]
    protected ?DateTime $updatedAt;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime('now');

        $this->exercises = new ArrayCollection();
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
    public function getExercises(): Collection|array
    {
        return $this->exercises;
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

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param string $id
     * @return ExerciseCategory
     */
    public function setId(string $id): ExerciseCategory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param User|null $user
     * @return ExerciseCategory
     */
    public function setUser(?User $user): ExerciseCategory
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param array|Collection $exercises
     * @return ExerciseCategory
     */
    public function setExercises(Collection|array $exercises): ExerciseCategory
    {
        $this->exercises = $exercises;
        return $this;
    }

    /**
     * @param string $name
     * @return ExerciseCategory
     */
    public function setName(string $name): ExerciseCategory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string|null $description
     * @return ExerciseCategory
     */
    public function setDescription(?string $description): ExerciseCategory
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return ExerciseCategory
     */
    public function setCreatedAt(DateTime $createdAt): ExerciseCategory
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return ExerciseCategory
     */
    public function setUpdatedAt(?DateTime $updatedAt): ExerciseCategory
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // ----------------------------------------------------------------
    // Extra Methods
    // ----------------------------------------------------------------

}
