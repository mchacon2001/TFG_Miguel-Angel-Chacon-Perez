<?php

namespace App\Entity\Document;

use App\Entity\Project\AdditionalProjectData;
use App\Repository\Document\DocumentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: DocumentTypeRepository::class)]
#[Table(name: "document_type")]
class DocumentType
{
    const ENTITY_TYPE_USER = "user";
    const REQUIRED_DOCUMENT = true;

    const NON_REQUIRED_DOCUMENT = false;

    // ----------------------------------------------------------------
    // Primary Key
    // ----------------------------------------------------------------

    #[Id]
    #[Column(name: 'id', type: "integer")]
    #[GeneratedValue(strategy: 'AUTO')]
    private int $id;

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    #[OneToMany(mappedBy: "documentType", targetEntity: Document::class, cascade: ["persist", "remove"])]
    private array|Collection $documents;

    // ----------------------------------------------------------------
    // Fields
    // ----------------------------------------------------------------

    #[Column(name: "name", type: "string", unique: false, nullable: false)]
    public string $name;

    #[Column(name: "required_document", type: "boolean", unique: false, nullable: false)]
    protected bool $requiredDocument;

    #[Column(name: "entity_type", type: "string", unique: false, nullable: false)]
    protected string $entityType;

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    public function __construct()
    {
        $this->requiredDocument = self::NON_REQUIRED_DOCUMENT;
        $this->entityType = self::ENTITY_TYPE_USER;
        $this->documents = new ArrayCollection();
    }

    // ----------------------------------------------------------------
    // Getter Methods
    // ----------------------------------------------------------------

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isRequiredDocument(): bool
    {
        return $this->requiredDocument;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @return array|Collection
     */
    public function getDocuments(): Collection|array
    {
        return $this->documents;
    }

    // ----------------------------------------------------------------
    // Magic Methods
    // ----------------------------------------------------------------

    /**
     * @param int $id
     * @return DocumentType
     */
    public function setId(int $id): DocumentType
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return DocumentType
     */
    public function setName(string $name): DocumentType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param bool $requiredDocument
     * @return DocumentType
     */
    public function setRequiredDocument(bool $requiredDocument): DocumentType
    {
        $this->requiredDocument = $requiredDocument;
        return $this;
    }

    /**
     * @param string $entityType
     * @return DocumentType
     */
    public function setEntityType(string $entityType): DocumentType
    {
        $this->entityType = $entityType;
        return $this;
    }

    /**
     * @param array|Collection $documents
     * @return DocumentType
     */
    public function setDocuments(Collection|array $documents): DocumentType
    {
        $this->documents = $documents;
        return $this;
    }

    // ----------------------------------------------------------------
    // Extra Methods
    // ----------------------------------------------------------------




}