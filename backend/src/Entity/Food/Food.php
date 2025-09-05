<?php

namespace App\Entity\Food;

use App\Entity\Diet\DietHasFood;
use App\Entity\User\User;
use App\Entity\Diet\DailyIntake;
use App\Repository\Food\FoodRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;

#[Entity(repositoryClass: FoodRepository::class)]
#[Table(name: "food")]
class Food
{
    // Constants declaration
    const UPLOAD_FILES_PATH = 'food';
    // ----------------------------------------------------------------
    // Primary Key
    // ----------------------------------------------------------------
    #[Id]
    #[Column(type: "string", unique: true)]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: "doctrine.uuid_generator")]
    protected string $id;

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    #[ManyToOne(targetEntity: User::class, inversedBy: 'food')]
    #[JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: true, onDelete: "SET NULL")]
    private ?User $user;
    
    #[OneToMany(mappedBy: "food", targetEntity: DietHasFood::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection|array $dietHasFood;

    #[OneToMany(mappedBy: "food", targetEntity: DailyIntake::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection|array $dailyIntake;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(type: "string", length: 100)]
    private string $name;

    #[Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[Column(type: "float", nullable: true)]
    private float $calories;

    #[Column(type: "float", nullable: true)]
    private float $proteins;

    #[Column(type: "float", nullable: true)]
    private float $carbs;

    #[Column(type: "float", nullable: true)]
    private float $fats;

    // ----------------------------------------------------------------
    // MAGIC METHODS
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->dietHasFood = new ArrayCollection();
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
     * @return Collection|array
     */
    public function getDietHasFood(): Collection|array
    {
        return $this->dietHasFood;
    }

    /**
     * @return Collection|array
     */
    public function getDailyIntake(): Collection|array
    {
        return $this->dailyIntake;
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
     * @return float|null
     */
    public function getCalories(): ?float
    {
        return $this->calories;
    }

    /**
     * @return float|null
     */
    public function getProteins(): ?float
    {
        return $this->proteins;
    }

    /**
     * @return float|null
     */
    public function getCarbs(): ?float
    {
        return $this->carbs;
    }

    /**
     * @return float|null
     */
    public function getFats(): ?float
    {
        return $this->fats;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------
    public function setId(string $id): Food
    {
        $this->id = $id;
        return $this;
    }
    /**
     * @param User|null $user
     * @return Food
     */
    public function setUser(?User $user): Food
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $name
     * @return Food
     */
    public function setName(string $name): Food
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @param string|null $description
     * @return Food
     */
    public function setDescription(?string $description): Food
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param float|null $calories
     * @return Food
     */
    public function setCalories(?float $calories): Food
    {
        $this->calories = $calories;
        return $this;
    }

    /**
     * @param float|null $proteins
     * @return Food
     */
    public function setProteins(?float $proteins): Food
    {
        $this->proteins = $proteins;
        return $this;
    }

    /**
     * @param float|null $carbs
     * @return Food
     */
    public function setCarbs(?float $carbs): Food
    {
        $this->carbs = $carbs;
        return $this;
    }

    /**
     * @param float|null $fats
     * @return Food
     */
    public function setFats(?float $fats): Food
    {
        $this->fats = $fats;
        return $this;
    }

    /**
     * @param Collection|array $dietHasFood
     * @return Food
     */
    public function setDietHasFood(Collection|array $dietHasFood): Food
    {
        $this->dietHasFood = $dietHasFood;
        return $this;
    }
}
