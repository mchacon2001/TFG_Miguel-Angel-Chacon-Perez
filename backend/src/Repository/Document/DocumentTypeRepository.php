<?php

namespace App\Repository\Document;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentType;
use App\Utils\Storage\DoctrineStorableObject;
use Doctrine\ORM\EntityRepository;


class DocumentTypeRepository extends EntityRepository
{
    use DoctrineStorableObject;

    /**
     * @param int $documentTypeId
     * @return DocumentType|null
     */
    public function findDocumentType(int $documentTypeId): ?DocumentType
    {
        return $this->find($documentTypeId);
    }
}
