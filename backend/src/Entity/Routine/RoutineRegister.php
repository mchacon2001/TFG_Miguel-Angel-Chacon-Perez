<?php

namespace App\Entity\Routine;

use App\Entity\User\User;
use DateTime;
use App\Repository\Routine\RoutineRegisterRepository;
use App\Entity\Routine\RoutineRegisterExercises;
use App\Entity\Routine\Routine;
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


#[Entity(repositoryClass: RoutineRegisterRepository::class)]
#[Table(name: "routine_register")]
class RoutineRegister
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

    #[OneToMany(targetEntity: RoutineRegisterExercises::class, mappedBy: 'routineRegister')]
    private Collection|array $routineRegisterExercises;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'routineRegister')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Routine::class, inversedBy: 'routineRegister')]
    #[JoinColumn(name: 'routine_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Routine $routines;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "day", type: "integer", unique: false, nullable: false)]
    protected int $day;

    #[Column(name: "start_time", type: "datetime", unique: false, nullable: false)]
    protected DateTime $startTime;

    #[Column(name: "end_time", type: "datetime", unique: false, nullable: true)]
    protected ?DateTime $endTime;

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
    }

    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------

    public function getId(): string
    {
        return $this->id;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function getRoutines(): Routine
    {
        return $this->routines;
    }

    public function getDay(): int
    {
        return $this->day;
    }
    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }
    public function getEndTime(): ?DateTime
    {
        return $this->endTime;
    }
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getRoutineRegisterExercises(): Collection|array
    {
        return $this->routineRegisterExercises;
    }
    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------
    public function setUser(User $user): RoutineRegister
    {
        $this->user = $user;
        return $this;
    }
    public function setRoutines(Routine $routines): RoutineRegister
    {
        $this->routines = $routines;
        return $this;
    }
    
    public function setDay(int $day): RoutineRegister
    {
        $this->day = $day;
        return $this;
    }
    public function setStartTime(DateTime $startTime): RoutineRegister
    {
        $this->startTime = $startTime;
        return $this;
    }
    public function setEndTime(?DateTime $endTime): RoutineRegister
    {
        $this->endTime = $endTime;
        return $this;
    }
    public function setUpdatedAt(?DateTime $updatedAt): RoutineRegister
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    public function setId(string $id): RoutineRegister
    {
        $this->id = $id;
        return $this;
    }
    public function setCreatedAt(DateTime $createdAt): RoutineRegister
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setRoutineRegisterExercises(Collection|array $routineRegisterExercises): RoutineRegister
    {
        $this->routineRegisterExercises = $routineRegisterExercises;
        return $this;
    }

}