<?php


namespace App\Entity\User;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use App\Entity\User\User;

#[Entity]
#[Table(name: "user_has_mental_stats")]
class UserHasMentalStats
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

    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'userHasMentalStats', fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: "CASCADE")]
    private User $user;


    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "mood", type: "integer", nullable: false)]
    private int $mood;

    #[Column(name: "sleepQuality", type: "integer", nullable: false)]
    private int $sleepQuality;

    #[Column(name: "recordedAt", type: "datetime", nullable: false)]
    private \DateTime $recordedAt;

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
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getMood(): float
    {
        return $this->mood;
    }

    /**
     * @return int
     */
    public function getSleepQuality(): int
    {
        return $this->sleepQuality;
    }


    /**
     * @return \DateTime
     */
    public function getRecordedAt(): \DateTime
    {
        return $this->recordedAt;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param float $weight
     * @return $this
     */
    public function setMood(int $mood): self
    {
        $this->mood = $mood;
        return $this;
    }

    /**
     * @param int $sleepQuality
     * @return $this
     */
    public function setSleepQuality(int $sleepQuality): self
    {
        $this->sleepQuality = $sleepQuality;
        return $this;
    }

    /**
     * @param \DateTime $recordedAt
     * @return $this
     */
    public function setRecordedAt(\DateTime $recordedAt): self
    {
        $this->recordedAt = $recordedAt;
        return $this;
    }

    // ----------------------------------------------------------------
}
