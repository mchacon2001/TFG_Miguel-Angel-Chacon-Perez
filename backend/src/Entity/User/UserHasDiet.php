<?php


namespace App\Entity\User;

use App\Entity\Diet\Diet;
use App\Repository\User\UserHasDietRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\CustomIdGenerator;

#[Entity(repositoryClass: UserHasDietRepository::class)]
#[Table(name: "user_has_diet")]
class UserHasDiet
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

    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'userDiets',  fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Diet::class, cascade:["persist"], inversedBy: 'userDiets', fetch: 'EAGER')]
    #[JoinColumn(name: 'diet_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Diet $diet;

    // ----------------------------------------------------------------
    // fields
    // ----------------------------------------------------------------

    #[Column(name: 'selected_diet', type: 'boolean', unique: false, nullable: true)]
    private ?bool $selectedDiet = false;

    #[Column(name: 'created_at', type: 'datetime', nullable: false)]
    protected \DateTime $createdAt;

    // ----------------------------------------------------------------
    // Constructor
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * @return Diet
     */
    public function getDiet(): Diet
    {
        return $this->diet;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSelectedDiet(): bool
    {
        return $this->selectedDiet ?? false;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param User $user
     * @return UserHasDiet
     */
    public function setUser(User $user): UserHasDiet
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Diet $diet
     * @return UserHasDiet
     */
    public function setDiet(Diet $diet): UserHasDiet
    {
        $this->diet = $diet;
        return $this;
    }

    /**
     * @param string $id
     * @return UserHasDiet
     */
    public function setId(string $id): UserHasDiet
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param bool $selectedDiet
     * @return UserHasDiet
     */
    public function setSelectedDiet(bool $selectedDiet): UserHasDiet
    {
        $this->selectedDiet = $selectedDiet;
        return $this;
    }

    // ----------------------------------------------------------------
}
