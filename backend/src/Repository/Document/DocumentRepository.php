<?php

namespace App\Repository\Document;

use App\Entity\Document\Document;
use App\Utils\Storage\DoctrineStorableObject;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;


class DocumentRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A DOCUMENT
     * ES: FUNCIÓN PARA CREAR UN DOCUMENTO
     *
     * @param string $originalName
     * @param string $fileName
     * @param string $extension
     * @param string $subdirectory
     * @param bool $status
     * @return Document
     */
    // --------------------------------------------------------------
    public function createDocument(
        string $originalName,
        string $fileName,
        string $extension,
        string $subdirectory,
        bool $status = true
    ): Document
    {
        $document = (new Document())
            ->setOriginalName($originalName)
            ->setFileName($fileName)
            ->setExtension($extension)
            ->setSubdirectory($subdirectory)
            ->setStatus($status);

        $this->save($this->_em, $document);

        return $document;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A DOCUMENT
     * ES: FUNCIÓN PARA ENCONTRAR UN DOCUMENTO
     *
     * @param string $documentId
     * @return object|Document
     */
    // --------------------------------------------------------------
    public function findDocument(string $documentId): ?Document
    {
        return $this->find($documentId);
    }
    // --------------------------------------------------------------


    /**
     * @throws NonUniqueResultException
     */
    public function findDocumentById(string $documentId, ?bool $array = false): Document|array|null
    {
        return $this->createQueryBuilder('d')
            ->where('d.id = :documentId')
            ->setParameter('documentId', $documentId)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A DOCUMENT
     * ES: FUNCIÓN PARA ELIMINAR UN DOCUMENTO
     *
     * @param Document $document
     * @return void
     */
    // --------------------------------------------------------------
    public function deleteDocument(Document $document): void
    {
        $this->delete($this->_em, $document);
    }
    // --------------------------------------------------------------
}
