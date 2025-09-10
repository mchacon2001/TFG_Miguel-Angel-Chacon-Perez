<?php


namespace App\Entity\User;

use App\Entity\Routine\Routine;
use App\Repository\User\UserHasRoutineRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\CustomIdGenerator;

#[Entity(repositoryClass: UserHasRoutineRepository::class)]
#[Table(name: "user_has_routine")]
class UserHasRoutine
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

    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'userRoutines',  fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Routine::class, cascade:["persist"], inversedBy: 'userRoutines', fetch: 'EAGER')]
    #[JoinColumn(name: 'routine_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Routine $routine;

    // ----------------------------------------------------------------
    // fields
    // ----------------------------------------------------------------
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
     * @return Routine
     */
    public function getRoutine(): Routine
    {
        return $this->routine;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param User $user
     * @return UserHasRoutine
     */
    public function setUser(User $user): UserHasRoutine
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Routine $routine
     * @return UserHasRoutine
     */
    public function setRoutine(Routine $routine): UserHasRoutine
    {
        $this->routine = $routine;
        return $this;
    }

    /**
     * @param string $id
     * @return UserHasRoutine
     */
    public function setId(string $id): UserHasRoutine
    {
        $this->id = $id;
        return $this;
    }

    // ----------------------------------------------------------------
}
