<?php


namespace App\Entity\User;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use App\Entity\User\User;

#[Entity]
#[Table(name: "user_has_physical_stats")]
class UserHasPhysicalStats
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

    #[ManyToOne(targetEntity: User::class, cascade:["persist"], inversedBy: 'userHasPhysicalStats', fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: "CASCADE")]
    private User $user;


    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "weight", type: "float", nullable: false)]
    private float $weight;

    #[Column(name: "height", type: "float", nullable: false)]
    private float $height;

    #[Column(name: "body_fat", type: "float", nullable: false)]
    private ?float $bodyFat = null;

    #[Column(name: "bmi", type: "float", nullable: false)]
    private ?float $bmi = null;

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
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return float|null
     */
    public function getBodyFat(): ?float
    {
        return $this->bodyFat;
    }

    /**
     * @return float|null
     */
    public function getBmi(): ?float
    {
        return $this->bmi;
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
    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param float|null $bodyFat
     * @return $this
     */
    public function setBodyFat(?float $bodyFat): self
    {
        $this->bodyFat = $bodyFat;
        return $this;
    }

    /**
     * @param float|null $bmi
     * @return $this
     */
    public function setBmi(?float $bmi): self
    {
        $this->bmi = $bmi;
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
