<?php

namespace App\Entity\Routine;

use App\Entity\Exercise\Exercise;
use App\Repository\Routine\RoutineHasExerciseRepository;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;


#[Entity(repositoryClass: RoutineHasExerciseRepository::class)]
#[Table(name: "routine_has_exercise")]
class RoutineHasExercise
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

    #[ManyToOne(targetEntity: Exercise::class, inversedBy: 'routineHasExercise')]
    #[JoinColumn(name: 'exercise_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Exercise $exercise;

    #[ManyToOne(targetEntity: Routine::class, inversedBy: 'routineHasExercise')]
    #[JoinColumn(name: 'routine_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Routine $routines;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "sets", type: "integer", unique: false, nullable: false)]
    protected int $sets;

    #[Column(name: "reps", type: "integer", unique: false, nullable: false)]
    protected int $reps;

    #[Column(name: "rest_time", type: "integer", unique: false, nullable: false)]
    protected int $restTime;

    #[Column(name: "day", type: "integer", unique: false, nullable: false)]
    private int $day;

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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return Exercise
     */
    public function getExercise(): Exercise
    {
        return $this->exercise;
    }

    /**
     * @return Routine
     */
    public function getRoutines(): Routine
    {
        return $this->routines;
    }

    /**
     * @return int
     */
    public function getSets(): int
    {
        return $this->sets;
    }

    /**
     * @return int
     */
    public function getReps(): int
    {
        return $this->reps;
    }

    /**
     * @return int
     */
    public function getRestTime(): int
    {
        return $this->restTime;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
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
     * @return RoutineHasExercise
     */
    public function setId(string $id): RoutineHasExercise
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param Exercise $exercise
     * @return RoutineHasExercise
     */
    public function setExercise(Exercise $exercise): RoutineHasExercise
    {
        $this->exercise = $exercise;
        return $this;
    }

    /**
     * @param Routine $routines
     * @return RoutineHasExercise
     */
    public function setRoutines(Routine $routines): RoutineHasExercise
    {
        $this->routines = $routines;
        return $this;
    }

    /**
     * @param int $sets
     * @return RoutineHasExercise
     */
    public function setSets(int $sets): RoutineHasExercise
    {
        $this->sets = $sets;
        return $this;
    }

    /**
     * @param int $reps
     * @return RoutineHasExercise
     */
    public function setReps(int $reps): RoutineHasExercise
    {
        $this->reps = $reps;
        return $this;
    }

    /**
     * @param int $restTime
     * @return RoutineHasExercise
     */
    public function setRestTime(int $restTime): RoutineHasExercise
    {
        $this->restTime = $restTime;
        return $this;
    }

    /**
     * @param int $day
     * @return RoutineHasExercise
     */
    public function setDay(int $day): RoutineHasExercise
    {
        $this->day = $day;
        return $this;
    }
    
    /**
     * @param DateTime $createdAt
     * @return RoutineHasExercise
     */
    public function setCreatedAt(DateTime $createdAt): RoutineHasExercise
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return RoutineHasExercise
     */
    public function setUpdatedAt(?DateTime $updatedAt): RoutineHasExercise
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
