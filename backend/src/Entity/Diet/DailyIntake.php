<?php


namespace App\Entity\Diet;

use App\Entity\Diet\Diet;
use App\Entity\Food\Food;
use App\Entity\User\User;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use DateTime;
use App\Repository\Diet\DailyIntakeRepository;


#[Entity(repositoryClass: DailyIntakeRepository::class)]
#[Table(name: "daily_intake")]
class DailyIntake
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

    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'dailyIntake',  fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Food::class, cascade:["persist"], inversedBy: 'dailyIntake', fetch: 'EAGER')]
    #[JoinColumn(name: 'food_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Food $food;

    // ----------------------------------------------------------------
    // fields
    // ----------------------------------------------------------------

    #[Column(name: 'amount', type: 'float', unique: false, nullable: false)]
    private float $amount;

    #[Column(type: "string", length: 20)]
    private string $mealType;

    #[Column(name: 'created_at', type: 'datetime', nullable: false)]
    protected DateTime $createdAt;

    // ----------------------------------------------------------------
    // Constructor
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

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
     * @return Food
     */
    public function getFood(): Food
    {
        return $this->food;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getMealType(): string
    {
        return $this->mealType;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param User $user
     * @return DailyIntake
     */
    public function setUser(User $user): DailyIntake
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Food $food
     * @return DailyIntake
     */
    public function setFood(Food $food): DailyIntake
    {
        $this->food = $food;
        return $this;
    }

    /**
     * @param string $id
     * @return DailyIntake
     */
    public function setId(string $id): DailyIntake
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param float $amount
     * @return DailyIntake
     */
    public function setAmount(float $amount): DailyIntake
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $mealType
     * @return DailyIntake
     */
    public function setMealType(string $mealType): DailyIntake
    {
        $this->mealType = $mealType;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return DailyIntake
     */
    public function setCreatedAt(DateTime $createdAt): DailyIntake
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // ----------------------------------------------------------------
}
