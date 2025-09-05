<?php

namespace App\Repository\User;

use App\Entity\User\UserHasDocument;
use App\Utils\Storage\DoctrineStorableObject;
use Doctrine\ORM\EntityRepository;

class UserHasDocumentRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE A DOCUMENT OF A USER
     * ES: FUNCIÓN PARA ELIMINAR UN DOCUMENTO DE UN USUARIO
     *
     * @param UserHasDocument $userHasDocument
     * @return void
     */
    // --------------------------------------------------------------------------------------
    public function removeDocumentOfUser(UserHasDocument $userHasDocument): void
    {
        $document = $userHasDocument->getDocument()->setStatus(false);
        $this->save($this->_em, $document);
        $this->delete($this->_em, $userHasDocument);
    }
    // --------------------------------------------------------------------------------------
}