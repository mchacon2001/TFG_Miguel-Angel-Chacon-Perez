<?php

namespace App\Entity\EducativeResources;

use App\Repository\EducativeResource\EducativeResourceRepository;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;


#[Entity(repositoryClass: EducativeResourceRepository::class)]
#[Table(name: "educative_resource")]
class EducativeResources
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
    // Fields
    // ----------------------------------------------------------------
    #[Column(name: "tag", type: "string", unique: false, nullable: false)]
    private string $tag = 'training';

    #[Column(name: "isVideo", type: "boolean", unique: false, nullable: false)]
    private bool $isVideo = true;

    #[Column(name: "title", type: 'string', unique: false, nullable: false)]
    private string $title;

    #[Column(name: "youtubeUrl", type: 'string', unique: false, nullable: false)]
    private string $youtubeUrl;

    #[Column(name: "description", type: "string", unique: false, nullable: true)]
    protected ?string $description;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
    }
    public function __toString(): string
    {
        return $this->getId();
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
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }
    /**
     * @return bool
     */
    public function isVideo(): bool
    {
        return $this->isVideo;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @return string|null
     */
    public function getYoutubeUrl(): string
    {
        return $this->youtubeUrl;
    }
    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
     * @param string $title
     */
    public function setTitle(string $title) : EducativeResources
    {
        $this->title = $title;
        return $this;
    }
    /**
     * @param string $tag
     */
    public function setTag(string $tag): EducativeResources
    {
        $this->tag = $tag;
        return $this;
    }
    /**
     * @param bool $isVideo
     */
    public function setIsVideo(bool $isVideo): EducativeResources
    {
        $this->isVideo = $isVideo;
        return $this;
    }
    
    /**
     * @param string|null $youtubeUrl
     */
    public function setYoutubeUrl(string $youtubeUrl): EducativeResources
    {
        $this->youtubeUrl = $youtubeUrl;
        return $this;
    }
    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): EducativeResources
    {
        $this->description = $description;
        return $this;
    }
    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): EducativeResources
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    // ----------------------------------------------------------------
}