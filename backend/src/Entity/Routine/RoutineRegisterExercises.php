<?php

namespace App\Entity\Routine;

use App\Entity\Exercise\Exercise;
use App\Repository\Routine\RoutineRegisterExercisesRepository;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;


#[Entity(repositoryClass: RoutineRegisterExercisesRepository::class)]
#[Table(name: "routine_register_exercises")]
class RoutineRegisterExercises
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

    #[ManyToOne(targetEntity: RoutineRegister::class, inversedBy: 'routineRegisterExercises')]
    #[JoinColumn(name: 'routineRegister_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private RoutineRegister $routineRegister;


    #[ManyToOne(targetEntity: Exercise::class, inversedBy: 'routineRegisterExercises')]
    #[JoinColumn(name: 'exercise_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Exercise $exercise;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "sets", type: "integer", unique: false, nullable: false)]
    private int $sets;

    #[Column(name: "reps", type: "integer", unique: false, nullable: false)]
    private int $reps;

    #[Column(name: "weight", type: "float", unique: false, nullable: true)]
    private ?float $weight;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

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
    public function getRoutineRegister(): RoutineRegister
    {
        return $this->routineRegister;
    }
    public function getExercise(): Exercise
    {
        return $this->exercise;
    }
    public function getSets(): int
    {
        return $this->sets;
    }
    public function getReps(): int
    {
        return $this->reps;
    }
    public function getWeight(): ?float
    {
        return $this->weight;
    }
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    public function setRoutineRegister(RoutineRegister $routineRegister): RoutineRegisterExercises
    {
        $this->routineRegister = $routineRegister;
        return $this;
    }
    public function setExercise(Exercise $exercise): RoutineRegisterExercises
    {
        $this->exercise = $exercise;
        return $this;
    }
    public function setSets(int $sets): RoutineRegisterExercises
    {
        $this->sets = $sets;
        return $this;
    }
    public function setReps(int $reps): RoutineRegisterExercises
    {
        $this->reps = $reps;
        return $this;
    }
    public function setWeight(?float $weight): RoutineRegisterExercises
    {
        $this->weight = $weight;
        return $this;
    }
    public function setCreatedAt(DateTime $createdAt): RoutineRegisterExercises
    {
        $this->createdAt = $createdAt;
        return $this;
    }

}