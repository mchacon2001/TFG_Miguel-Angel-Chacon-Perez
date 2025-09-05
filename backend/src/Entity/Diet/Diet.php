<?php
namespace App\Entity\Diet;

use App\Entity\User\User;
use App\Entity\User\UserHasDiet;
use App\Repository\Diet\DietRepository;
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

#[Entity(repositoryClass: DietRepository::class)]
#[Table(name: "diet")]
class Diet
{

    // Constants declaration
    const UPLOAD_FILES_PATH = 'routines';


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

    #[ManyToOne(targetEntity: User::class , inversedBy: 'diet')]
    #[JoinColumn(name: "creator_id", referencedColumnName: "id", nullable: true, onDelete: "CASCADE")]
    private ?User $user;

    #[OneToMany(mappedBy: "diet", targetEntity: DietHasFood::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection|array $dietHasFood;

    #[OneToMany(mappedBy: "diet", targetEntity: UserHasDiet::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection|array $userDiets;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(type: "string")]
    private string $name;

    #[Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[Column(type: "string", )]
    private string $goal;

    #[Column(name: "created_at", type: "datetime")]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    protected ?DateTime $updatedAt;
    
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
        $this->dietHasFood = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id;
    }
    
    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------
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
     * @return string|null
     */
    public function getGoal(): ?string
    {
        return $this->goal;
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
     * @return Collection|array
     */
    public function getUserDiets(): Collection|array
    {
        return $this->userDiets;
    }
    
        

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------
    
    public function setId(string $id): Diet
    {
        $this->id = $id;
        return $this;
    }

    public function setUser(?User $user): Diet
    {
        $this->user = $user;
        return $this;
    }

    public function setDietHasFood(Collection|array $dietHasFood): Diet
    {
        $this->dietHasFood = $dietHasFood;
        return $this;
    }

    public function setUserDiets(Collection|array $userDiets): Diet
    {
        $this->userDiets = $userDiets;
        return $this;
    }

    public function setName(string $name): Diet
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(?string $description): Diet
    {
        $this->description = $description;
        return $this;
    }

    public function setGoal(?string $goal): Diet
    {
        $this->goal = $goal;
        return $this;
    }

    public function setCreatedAt(DateTime $createdAt): Diet
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(?DateTime $updatedAt): Diet
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
        /**
     * @param bool $toGainMuscle
     * @return Diet
     */
    public function setToGainMuscle(bool $toGainMuscle): Diet
    {
        $this->toGainMuscle = $toGainMuscle;
        return $this;
    }
    /**
     * @param bool $toMaintainWeight
     * @return Diet
     */
    public function setToMaintainWeight(bool $toMaintainWeight): Diet
    {
        $this->toMaintainWeight = $toMaintainWeight;
        return $this;
    }
    /**
     * @param bool $toLoseWeight
     * @return Diet
     */
    public function setToLoseWeight(bool $toLoseWeight): Diet   
    {
        $this->toLoseWeight = $toLoseWeight;
        return $this;
    }
    /**
     * @param bool $toImproveMentalHealth
     * @return Diet
     */
    public function setToImproveMentalHealth(bool $toImproveMentalHealth): Diet
    {
        $this->toImproveMentalHealth = $toImproveMentalHealth;
        return $this;
    }
    /**
     * @param bool $toImprovePhysicalHealth
     * @return Diet
     */
    public function setToImprovePhysicalHealth(bool $toImprovePhysicalHealth): Diet
    {
        $this->toImprovePhysicalHealth = $toImprovePhysicalHealth;
        return $this;
    }
    /**
     * @param bool $fixShoulder
     * @return Diet
     */
    public function setFixShoulder(bool $fixShoulder): Diet
    {
        $this->fixShoulder = $fixShoulder;
        return $this;
    }
    /**
     * @param bool $fixKnees
     * @return Diet
     */
    public function setFixKnees(bool $fixKnees): Diet
    {
        $this->fixKnees = $fixKnees;
        return $this;
    }
    /**
     * @param bool $fixBack
     * @return Diet
     */
    public function setFixBack(bool $fixBack): Diet
    {
        $this->fixBack = $fixBack;
        return $this;
    }
    /**
     * @param bool $rehab
     * @return Diet
     */
    public function setRehab(bool $rehab): Diet
    {
        $this->rehab = $rehab;
        return $this;
    }
}