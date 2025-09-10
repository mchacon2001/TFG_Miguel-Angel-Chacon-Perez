<?php

namespace App\Entity\User;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentType;
use App\Repository\User\UserHasDocumentRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity(repositoryClass: UserHasDocumentRepository::class)]
#[Table(name: "user_has_document", uniqueConstraints: [
    new UniqueConstraint(name: "user_has_document_unique_relation", columns: ["user_id", "document_id"])
])]
class UserHasDocument
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

    #[ManyToOne(targetEntity: User::class, inversedBy: 'documents')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private User $user;

    #[ManyToOne(targetEntity: Document::class)]
    #[JoinColumn(name: 'document_id', referencedColumnName: 'id', nullable: false,  onDelete: "CASCADE")]
    private Document $document;

    #[ManyToOne(targetEntity: DocumentType::class)]
    #[JoinColumn(name: 'document_type_id', referencedColumnName: 'id', nullable: true,  onDelete: "SET NULL")]
    private ?DocumentType $documentType;

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
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return DocumentType|null
     */
    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    // ----------------------------------------------------------------
    // Setter Methods
    // ----------------------------------------------------------------

    /**
     * @param User $user
     * @return UserHasDocument
     */
    public function setUser(User $user): UserHasDocument
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Document $document
     * @return UserHasDocument
     */
    public function setDocument(Document $document): UserHasDocument
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @param DocumentType|null $documentType
     * @return UserHasDocument
     */
    public function setDocumentType(?DocumentType $documentType): UserHasDocument
    {
        $this->documentType = $documentType;
        return $this;
    }

}