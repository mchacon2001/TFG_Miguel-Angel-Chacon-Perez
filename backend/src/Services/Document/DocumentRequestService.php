<?php

namespace App\Services\Document;

use App\Request\Document\GetDocumentRequest;
use App\Utils\Tools\APIJsonResponse;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentRequestService
{

    public function __construct(
        protected DocumentService $documentService,
        protected Security $token,
    )
    {
        // EMPTY
    }

    // -----------------------------------------------------
    /**
     * EN: REQUEST TO GET A DOCUMENT
     * ES: PETICIÓN PARA OBTENER UN DOCUMENTO
     *
     * @param GetDocumentRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------
    public function get(GetDocumentRequest $request): APIJsonResponse
    {
        $documentData = $this->documentService->getDocumentById($request->document, true);

        return new APIJsonResponse($documentData);
    }
    // -----------------------------------------------------


    // -----------------------------------------------------
    /**
     * EN: REQUEST TO RENDER A DOCUMENT
     * ES: PETICIÓN PARA RENDERIZAR UN DOCUMENTO
     *
     * @param GetDocumentRequest $request
     * @return Response
     */
    // -----------------------------------------------------
    public function render(GetDocumentRequest $request): Response
    {
        return $this->documentService->renderDocument($request->document);
    }
    // -----------------------------------------------------


    // -----------------------------------------------------
    /**
     * EN: REQUEST TO RENDER A DOCUMENT (NEW METHOD)
     * ES: PETICIÓN PARA RENDERIZAR UN DOCUMENTO (NUEVO MÉTODO)
     *
     * @param Request $request
     * @return Response
     */
    // -----------------------------------------------------
    public function renderDocument(Request $request): Response
    {
        return $this->documentService->renderDocument($request->attributes->get('document'));
    }
    // -----------------------------------------------------
}