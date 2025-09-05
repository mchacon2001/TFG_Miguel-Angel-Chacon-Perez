<?php

namespace App\Entity\Document;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use App\Repository\Document\DocumentRepository;

#[Entity(repositoryClass: DocumentRepository::class)]
#[Table(name: "document")]
class Document
{
    const STATUS_REMOVED = false;
    const STATUS_ENABLED = true;

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

    #[ManyToOne(targetEntity: DocumentType::class, inversedBy: "documents")]
    #[JoinColumn(name: "document_type_id", referencedColumnName: "id")]
    protected DocumentType $documentType;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "original_name", type: "string", unique: false, nullable: false)]
    protected string $originalName;

    #[Column(name: "extension", type: "string", unique: false, nullable: false)]
    protected string $extension;

    #[Column(name: "file_name", type: "string", unique: false, nullable: false)]
    protected string $fileName;

    #[Column(name: "subdirectory", type: "string", unique: false, nullable: false)]
    protected string $subdirectory;

    #[Column(name: "created_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime", unique: false, nullable: false)]
    protected DateTime $updatedAt;

    #[Column(name: "status", type: "boolean", unique: false, nullable: false)]
    protected bool $status;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

        $this->status    = self::STATUS_ENABLED;
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
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getSubdirectory(): string
    {
        return $this->subdirectory;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @return DocumentType
     */
    public function getDocumentType(): DocumentType
    {
        return $this->documentType;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param string $id
     * @return Document
     */
    public function setId(string $id): Document
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $originalName
     * @return Document
     */
    public function setOriginalName(string $originalName): Document
    {
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * @param string $extension
     * @return Document
     */
    public function setExtension(string $extension): Document
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @param string $fileName
     * @return Document
     */
    public function setFileName(string $fileName): Document
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @param string $subdirectory
     * @return Document
     */
    public function setSubdirectory(string $subdirectory): Document
    {
        $this->subdirectory = $subdirectory;
        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return Document
     */
    public function setCreatedAt(DateTime $createdAt): Document
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param DateTime $updatedAt
     * @return Document
     */
    public function setUpdatedAt(DateTime $updatedAt): Document
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @param bool $status
     * @return Document
     */
    public function setStatus(bool $status): Document
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param DocumentType $documentType
     * @return Document
     */
    public function setDocumentType(DocumentType $documentType): Document
    {
        $this->documentType = $documentType;
        return $this;
    }

    // ----------------------------------------------------------------
    // Extra Methods
    // ----------------------------------------------------------------


}