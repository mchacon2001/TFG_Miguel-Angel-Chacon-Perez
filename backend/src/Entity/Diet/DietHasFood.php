<?php
namespace App\Entity\Diet;

use App\Entity\Food\Food;
use App\Entity\Diet\Diet;
use App\Repository\Diet\DietHasFoodRepository;
use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

#[Entity(repositoryClass: DietHasFoodRepository::class)]
#[Table(name: "diet_has_food")]
class DietHasFood
{
    // -------------------------------------------------
    // Primary Key
    // -------------------------------------------------

    #[Id]
    #[Column(type: "string", unique: true)]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: "doctrine.uuid_generator")]
    protected string $id;

    // -------------------------------------------------
    // Relationships
    // -------------------------------------------------

    #[ManyToOne(targetEntity: Diet::class, inversedBy: "dietHasFood")]
    #[JoinColumn(name: "diet_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Diet $diet;

    #[ManyToOne(targetEntity: Food::class)]
    #[JoinColumn(name: "food_id", referencedColumnName: "id", nullable: false)]
    private Food $food;

    // -------------------------------------------------
    // Fields
    // -------------------------------------------------

    #[Column(type: "string", length: 20)]
    private string $dayOfWeek;

    #[Column(type: "string", length: 20)]
    private string $mealType;

    #[Column(type: "float")]
    private float $amount;

    #[Column(type: "string", nullable: true)]
    private ?string $notes = null;

    #[Column(name: "created_at", type: "datetime")]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    protected ?DateTime $updatedAt;

    // -------------------------------------------------
    // Magic Methods
    // -------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
    }

    // -------------------------------------------------
    // Getters Methods
    // -------------------------------------------------

    /**
     * @return Diet
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Diet
     */
    public function getDiet(): Diet
    {
        return $this->diet;
    }
    /**
     * @return Food
     */
    public function getFood(): Food
    {
        return $this->food;
    }
    /**
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }
    /**
     * @return string
     */
    public function getMealType(): string
    {
        return $this->mealType;
    }
    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
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

    // -------------------------------------------------
    // Setter Methods
    // -------------------------------------------------
    
    /**
     * @param string $id
     * @return DietHasFood
     */
    public function setId(string $id): DietHasFood
    {
        $this->id = $id;
        return $this;
    }
    /**
     * @param Diet $diet
     * @return DietHasFood
     */
    public function setDiet(Diet $diet): DietHasFood
    {
        $this->diet = $diet;
        return $this;
    }
    /**
     * @param Food $food
     * @return DietHasFood
     */
    public function setFood(Food $food): DietHasFood
    {
        $this->food = $food;
        return $this;
    }
    /**
     * @param string $dayOfWeek
     * @return DietHasFood
     */
    public function setDayOfWeek(string $dayOfWeek): DietHasFood
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }
    /**
     * @param string $mealType
     * @return DietHasFood
     */
    public function setMealType(string $mealType): DietHasFood
    {
        $this->mealType = $mealType;
        return $this;
    }
    /**
     * @param float $amount
     * @return DietHasFood
     */
    public function setAmount(float $amount): DietHasFood
    {
        $this->amount = $amount;
        return $this;
    }
    /**
     * @param string|null $notes
     * @return DietHasFood
     */
    public function setNotes(?string $notes): DietHasFood
    {
        $this->notes = $notes;
        return $this;
    }
    /**
     * @param DateTime $createdAt
     * @return DietHasFood
     */
    public function setCreatedAt(DateTime $createdAt): DietHasFood
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    /**
     * @param DateTime|null $updatedAt
     * @return DietHasFood
     */
    public function setUpdatedAt(?DateTime $updatedAt): DietHasFood
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
