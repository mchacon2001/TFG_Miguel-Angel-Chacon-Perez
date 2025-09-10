<?php

namespace App\Request\Document;

use App\Entity\Document\Document;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;

class GetDocumentRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Document::class, message: "El documento introducido no existe en la base de datos")]
    public string $document;
}