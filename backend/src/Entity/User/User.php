<?php

namespace App\Entity\User;

use App\Entity\Diet\DailyIntake;
use DateTime;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineCategory;
use App\Entity\Exercise\Exercise;
use App\Entity\Exercise\ExerciseCategory;
use App\Entity\Diet\Diet;
use App\Entity\Food\Food;
use App\Entity\User\UserHasRole;
use App\Entity\User\UserHasPermission;
use App\Entity\User\UserHasDocument;
use App\Entity\User\UserHasRoutine;
use App\Entity\Routine\RoutineRegister;
use App\Entity\Routine\RoutineRegisterExercise;
use App\Entity\User\UserHasExercise;
use App\Entity\Document\Document;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\User\UserRepository;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: "user")]
class User implements JWTUserInterface, PasswordAuthenticatedUserInterface
{

    const UPLOAD_FILES_PATH = 'users';

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

    #[OneToMany(mappedBy: "user", targetEntity: UserHasRole::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    protected array|Collection $userRoles;

    #[OneToMany(mappedBy: "user", targetEntity: UserHasPermission::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    protected array|Collection $userPermissions;

    #[OneToMany(mappedBy: "user", targetEntity: UserHasDocument::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private array|Collection $documents;

    #[ManyToOne(targetEntity: Document::class)]
    #[JoinColumn(name: 'profile_img_id', referencedColumnName: 'id', nullable: true,  onDelete: "SET NULL")]
    private ?Document $profileImg;

    #[OneToMany(mappedBy: "user", targetEntity: ExerciseCategory::class, cascade: ["persist"])]
    private array|Collection $exerciseCategories;

    #[OneToMany(mappedBy: "user", targetEntity: Exercise::class, cascade: ["persist"])]
    private array|Collection $exercises;

    #[OneToMany(mappedBy: "user", targetEntity: RoutineCategory::class, cascade: ["persist"])]
    private array|Collection $routineCategory;

    #[OneToMany(mappedBy: "user", targetEntity: Routine::class, cascade: ["persist"])]
    private array|Collection $routines;

    #[OneToMany(mappedBy: "user", targetEntity: Diet::class, cascade: ["persist"])]
    private array|Collection $diet;

    #[OneToMany(mappedBy: "user", targetEntity: Food::class, cascade: ["persist"])]
    private array|Collection $food;

    #[OneToMany(mappedBy: "user", targetEntity: UserHasPhysicalStats::class, cascade: ["persist"])]
    private array|Collection $userHasPhysicalStats;

    #[OneToMany(mappedBy: "user", targetEntity: UserHasMentalStats::class, cascade: ["persist"])]
    private array|Collection $userHasMentalStats;

    #[OneToMany(mappedBy: "user", targetEntity: UserHasRoutine::class, cascade: ["persist"])]
    private array|Collection $userRoutines;

    #[OneToMany(mappedBy: "user", targetEntity: RoutineRegister::class, cascade: ["persist"])]
    private array|Collection $routineRegister;

    #[OneToMany(mappedBy: "user", targetEntity: UserHasDiet::class, cascade: ["persist"])]
    private array|Collection $userDiets;

    #[OneToMany(mappedBy: "user", targetEntity: DailyIntake::class, cascade: ["persist"])]
    private array|Collection $dailyIntake;


    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "name", type: "string", unique: false, nullable: false)]
    protected string $name;

    #[Column(name: "email", type: 'string', unique: true, nullable: false)]
    private string $email;

    #[Column(name: "target_weight", type: 'float', unique: false, nullable: false)]
    private float $targetWeight;

    #[Column(name: "sex", type: 'string', unique: false, nullable: false)]
    private string $sex;

    #[Column(name: "birthdate", type: 'datetime', unique: false, nullable: false)]
    private DateTime $birthdate;

    #[Column(name: "password", type: 'string', unique: false, nullable: false)]
    private string $password;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", unique: false, nullable: true)]
    protected ?DateTime $updatedAt;

    #[Column(name: "last_login_at", type: "datetime", unique: false, nullable: true)]
    protected ?DateTime $lastLogin;

    #[Column(name: "active", type: "boolean", unique: false, nullable: false)]
    private bool $active;

    #[Column(name: "temporal_hash", type: "string", unique: false, nullable: true)]
    protected ?string $temporalHash;

    #[Column(name: "toGainMuscles", type: "boolean", unique: false, nullable: true)]
    private ?bool $toGainMuscle;

    #[Column(name: "toMaintainWeight", type: "boolean", unique: false, nullable: true)]
    private ?bool $toMaintainWeight;

    #[Column(name: "toLoseWeight", type: "boolean", unique: false, nullable: true)]
    private ?bool $toLoseWeight;

    #[Column(name: "toImproveMentalHealth", type: "boolean", unique: false, nullable: true)]
    private ?bool $toImproveMentalHealth;

    #[Column(name: "toImprovePhysicalHealth", type: "boolean", unique: false, nullable: true)]
    private ?bool $toImprovePhysicalHealth;

    #[Column(name: "fixShoulder", type: "boolean", unique: false, nullable: true)]
    private ?bool $fixShoulder;

    #[Column(name: "fixKnees", type: "boolean", unique: false, nullable: true)]
    private ?bool $fixKnees;

    #[Column(name: "fixBack", type: "boolean", unique: false, nullable: true)]
    private ?bool $fixBack;

    #[Column(name: "rehab", type: "boolean", unique: false, nullable: true)]
    private ?bool $rehab;

    // ----------------------------------------------------------------
    // Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->active    = true;
        $this->createdAt = new DateTime('now');

        $this->userRoles = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->userPermissions = new ArrayCollection();
        $this->exerciseCategories = new ArrayCollection();
        $this->exercises = new ArrayCollection();
        $this->routineCategory = new ArrayCollection();
        $this->routines = new ArrayCollection();
    }        

    // ----------------------------------------------------------------
    // Authentication Methods
    // ----------------------------------------------------------------

    public static function createFromPayload($username, array $payload): JWTUserInterface
    {
        return (new User())
            ->setId($payload['id'])
            ->setName($payload['name'])
            ->setEmail($payload['email']);
    }

    public function getRoles(): array
    {
        $roles = [];
        
        foreach ($this->getUserRoles() as $role) {
            $roles[] = $role->getRole()->getName();
        }
        if(!in_array("Usuario", $roles)) {
            $roles[] = "Usuario"; 
        }
        return $roles;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
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
     * @return array|Collection
     */
    public function getUserRoles(): Collection|array
    {
        return $this->userRoles;
    }

    /**
     * @return array|Collection
     */
    public function getUserPermissions(): Collection|array
    {
        return $this->userPermissions;
    }

    /**
     * @return array|Collection
     */
    public function getDocuments(): Collection|array
    {
        return $this->documents;
    }

    /**
     * @return Document|null
     */
    public function getProfileImg(): ?Document
    {
        return $this->profileImg;
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

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return float
     */
    public function getTargetWeight(): float
    {
        return $this->targetWeight;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
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
     * @return DateTime|null
     */
    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string|null
     */
    public function getTemporalHash(): ?string
    {
        return $this->temporalHash;
    }

    /**
     * @return array|Collection
     */
    public function getExerciseCategories(): Collection|array
    {
        return $this->exerciseCategories;
    }

    /**
     * @return array|Collection
     */
    public function getExercise(): Collection|array
    {
        return $this->exercises;
    }

    public function getRoutineCategory(): Collection|array
    {
        return $this->routineCategory;
    }

    /**
     * @return array|Collection
     */
    public function getRoutines(): Collection|array
    {
        return $this->routines;
    }

    /**
     * @return array|Collection
     */
    public function getDiet(): Collection|array
    {
        return $this->diet;
    }
    /**
     * @return array|Collection
     */
    public function getFood(): Collection|array
    {
        return $this->food;
    }
    /**
     * @return array|Collection
     */
    public function getUserHasPhysicalStats(): Collection|array
    {
        return $this->userHasPhysicalStats;
    }
    /**
     * @return array|Collection
     */
    public function getUserHasMentalStats(): Collection|array
    {
        return $this->userHasMentalStats;
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
    public function isToGainMuscle(): bool
    {
        return $this->toGainMuscle;
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
    public function isToMaintainWeight(): bool
    {
        return $this->toMaintainWeight;
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
    

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param string $id
     * @return User
     */
    public function setId(string $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param array|Collection $userRoles
     * @return User
     */
    public function setUserRoles(Collection|array $userRoles): User
    {
        $this->userRoles = $userRoles;
        return $this;
    }

    /**
     * @param array|Collection $userPermissions
     * @return User
     */
    public function setUserPermissions(Collection|array $userPermissions): User
    {
        $this->userPermissions = $userPermissions;
        return $this;
    }

    /**
     * @param array|Collection $documents
     * @return User
     */
    public function setDocuments(Collection|array $documents): User
    {
        $this->documents = $documents;
        return $this;
    }

    /**
     * @param Document|null $profileImg
     * @return User
     */
    public function setProfileImg(?Document $profileImg): User
    {
        $this->profileImg = $profileImg;
        return $this;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param float $targetWeight
     * @return User
     */
    public function setTargetWeight(float $targetWeight): User
    {
        $this->targetWeight = $targetWeight;
        return $this;
    }
    
    /**
     * @param string $sex
     * @return User
     */
    public function setSex(string $sex): User
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @param DateTime $birthdate
     * @return User
     */
    public function setBirthdate(DateTime $birthdate): User
    {
        $this->birthdate = $birthdate;
        return $this;
    }


    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return User
     */
    public function setUpdatedAt(?DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param DateTime|null $lastLogin
     * @return User
     */
    public function setLastLogin(?DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @param bool $active
     * @return User
     */
    public function setActive(bool $active): User
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @param string|null $temporalHash
     * @return User
     */
    public function setTemporalHash(?string $temporalHash): User
    {
        $this->temporalHash = $temporalHash;
        return $this;
    }

    /**
     * @param array|Collection $exerciseCategories
     * @return User
     */

    public function setExerciseCategories(Collection|array $exerciseCategories): User
    {
        $this->exerciseCategories = $exerciseCategories;
        return $this;
    }

    /**
     * @param array|Collection $exercises
     * @return User
     */
    public function setExercises(Collection|array $exercises): User
    {
        $this->exercises = $exercises;
        return $this;
    }

    /**
     * @param array|Collection $routines
     * @return User
     */
    public function setRoutines(Collection|array $routines): User
    {
        $this->routines = $routines;
        return $this;
    }

    /**
     * @param Collection|array $routineCategory
     * @return User
     */
    public function setRoutineCategory(Collection|array $routineCategory): User
    {
        $this->routineCategory = $routineCategory;
        return $this;
    }

    /**
     * @param Collection|array $diet
     * @return User
     */
    public function setDiet(Collection|array $diet): User
    {
        $this->diet = $diet;
        return $this;
    }
    /**
     * @param Collection|array $food
     * @return User
     */
    public function setFood(Collection|array $food): User
    {
        $this->food = $food;
        return $this;
    }
    /**
     * @param Collection|array $userHasPhysicalStats
     * @return User
     */
    public function setUserHasPhysicalStats(Collection|array $userHasPhysicalStats): User
    {
        $this->userHasPhysicalStats = $userHasPhysicalStats;
        return $this;
    }
    /**
     * @param Collection|array $userHasMentalStats
     * @return User
     */
    public function setUserHasMentalStats(Collection|array $userHasMentalStats): User
    {
        $this->userHasMentalStats = $userHasMentalStats;
        return $this;
    }
    /**
     * @param bool $toGainMuscle
     * @return User
     */
    public function setToGainMuscle(bool $toGainMuscle): User
    {
        $this->toGainMuscle = $toGainMuscle;
        return $this;
    }
    /**
     * @param bool $toLoseWeight
     * @return User
     */
    public function setToLoseWeight(bool $toLoseWeight): User
    {
        $this->toLoseWeight = $toLoseWeight;
        return $this;
    }
    /**
     * @param bool $toMaintainWeight
     * @return User
     */
    public function setToMaintainWeight(bool $toMaintainWeight): User
    {
        $this->toMaintainWeight = $toMaintainWeight;
        return $this;
    }
    /**
     * @param bool $toImproveMentalHealth
     * @return User
     */
    public function setToImproveMentalHealth(bool $toImproveMentalHealth): User
    {
        $this->toImproveMentalHealth = $toImproveMentalHealth;
        return $this;
    }
    /**
     * @param bool $toImprovePhysicalHealth
     * @return User
     */
    public function setToImprovePhysicalHealth(bool $toImprovePhysicalHealth): User
    {
        $this->toImprovePhysicalHealth = $toImprovePhysicalHealth;
        return $this;
    }
    
    /**
     * @param bool $fixShoulder
     * @return User
     */
    public function setFixShoulder(bool $fixShoulder): User
    {
        $this->fixShoulder = $fixShoulder;
        return $this;
    }
    /**
     * @param bool $fixKnees
     * @return User
     */
    public function setFixKnees(bool $fixKnees): User
    {
        $this->fixKnees = $fixKnees;
        return $this;
    }
    /**
     * @param bool $fixBack
     * @return User
     */
    public function setFixBack(bool $fixBack): User
    {
        $this->fixBack = $fixBack;
        return $this;
    }
    /**
     * @param bool $rehab
     * @return User
     */
    public function setRehab(bool $rehab): User
    {
        $this->rehab = $rehab;
        return $this;
    }
    
    // ----------------------------------------------------------------
    // Extra Methods
    // ----------------------------------------------------------------

    public function addUserRole(UserHasRole $newRole): self
    {
        if (!$this->userRoles->contains($newRole)) {
            $this->userRoles->add($newRole);
        }
        return $this;
    }

    public function removeUserRole(UserHasRole $userHasRole): self
    {

        if ($this->userRoles->contains($userHasRole)) {
            $this->userRoles->removeElement($userHasRole);
        }

        return $this;
    }

    public function addPermission(UserHasPermission $userHasPermission): self
    {
        if (!$this->userPermissions->contains($userHasPermission)) {
            $this->userPermissions->add($userHasPermission);
        }
        return $this;
    }

    public function removePermission(UserHasPermission $userHasPermission): self
    {
        if ($this->userPermissions->contains($userHasPermission)) {
            $this->userPermissions->removeElement($userHasPermission);
        }
        return $this;
    }

    // ----------------------------------------------------------------
    /**
     * EN: METHOD TO CHECK IF THE USER IS SUPERADMIN OR NOT
     * ES: MÉTODO PARA VERIFICAR SI EL USUARIO ES SUPERADMIN O NO
     *
     * @return bool
     */
    // ----------------------------------------------------------------
    public function isSuperAdmin(): bool
    {
        /** @var UserHasRole $userRole */
        foreach ($this->getUserRoles() as $userRole) {
            if ($userRole->getRole()->getId() == Role::ROLE_SUPER_ADMIN) {
                return true;
            }
        }

        return false;
    }


    public function isAdmin(): bool
    {
        /** @var UserHasRole $userRole */
        foreach ($this->getUserRoles() as $userRole) {
            if ($userRole->getRole()->getId() == Role::ROLE_ADMIN) {
                return true;
            }
        }

        return false;
    }
    // ----------------------------------------------------------------

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'lastLogin' => $this->lastLogin?->format('Y-m-d H:i:s'),
            'active' => $this->active,
            'roles' => $this->getRoles(),
            'permissions' => $this->getPermissionsSchema(),
            'imgProfile' => $this->getProfileImg()?->getId(),
            "isSuperAdmin" => $this->isSuperAdmin() ? true : false,
        ];
    }

    // ----------------------------------------------------------------
    /**
     * EN: METHOD TO GET THE PERMISSIONS OF THE USER
     * ES: MÉTODO PARA OBTENER LOS PERMISOS DEL USUARIO
     *
     * @return array
     */
    // ----------------------------------------------------------------
    public function getPermissionsSchema(): array
    { 
        $permissions = [];
        /** @var UserHasPermission $userPermission */
        foreach ($this->getUserPermissions() as $userPermission) {
            $permission = $userPermission->getPermission();
            $group = $permission->getPermissionGroup();

            if (!isset($permissions[$group->getName()])) {             
                $permissions[$group->getName()] = [];
            }

            $permissions[$group->getName()][] = $permission->getAction();
        }
        return $permissions;
    }

    // ----------------------------------------------------------------
    public function eraseCredentials()
    {
        // Implement eraseCredentials() method.
    }

}
