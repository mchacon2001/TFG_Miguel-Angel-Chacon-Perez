<?php


namespace App\Services\Document;


use App\Utils\Tools\FilterService;
use App\Utils\Tools\UploadFile;
use App\Entity\Document\Document;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Asset\Packages;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\Document\DocumentRepository;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class DocumentService extends AbstractController
{
    protected string $storagePath;
    /**
     * @var DocumentRepository
     */
    protected ObjectRepository $documentRepository;
    protected Filesystem $filesystem;


    public function __construct(KernelInterface $appKernel, Filesystem $filesystem, EntityManagerInterface $entityManager, Packages $packages)
    {
        $this->storagePath        = $appKernel->getProjectDir().'/resources';
        $this->documentRepository = $entityManager->getRepository(Document::class);
        $this->filesystem = $filesystem;
    }


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO UPLOAD A DOCUMENT
     * ES: SERVICIO PARA SUBIR UN DOCUMENTO
     *
     * @param UploadedFile $file
     * @param string $subdirectory
     * @return Document
     */
    // --------------------------------------------------------------
    public function uploadDocument(UploadedFile $file, string $subdirectory): Document
    {
        $uploadPath   = $this->storagePath . '/' . $subdirectory;
        $uploadedFile = UploadFile::upload($file, $uploadPath);

        return $this->documentRepository->createDocument(
            $file->getClientOriginalName(),
            $uploadedFile['fileName'],
            $uploadedFile['extension'],
            $subdirectory,
            true
        );
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO RENDER A DOCUMENT
     * ES: SERVICIO PARA RENDERIZAR UN DOCUMENTO
     *
     * @param string $documentId
     * @return BinaryFileResponse
     */
    // --------------------------------------------------------------
    public function renderDocument(string $documentId): BinaryFileResponse
    {
        $document = $this->documentRepository->findDocument($documentId);
        if ($document &&
            (
                $this->filesystem->exists($this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' . $document->getExtension()) ||
                $this->filesystem->exists($this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . $document->getExtension())
            )
        )
        {
            if($this->filesystem->exists($this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' . $document->getExtension())){
                $documentUrl = $this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' . $document->getExtension();
            }else{
                $documentUrl = $this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . $document->getExtension();
            }

            $response = new BinaryFileResponse($documentUrl);

            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                str_replace(' ', '_',$document->getFileName() . '.' . $document->getExtension())
            );

            return $response;
        } else {

            return new BinaryFileResponse('assets/images/not-found.jpg');
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO DOWNLOAD A DOCUMENT
     * ES: SERVICIO PARA DESCARGAR UN DOCUMENTO
     *
     * @param string $documentId
     * @return BinaryFileResponse
     */
    // --------------------------------------------------------------
    public function downloadDocument(string $documentId): BinaryFileResponse
    {
        $document = $this->documentRepository->findDocument($documentId);

        if (!$document || !$this->filesystem->exists($this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' .$document->getExtension())) {
            throw new FileNotFoundException($document->getOriginalName());
        }

        $fileContent = file_get_contents($this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' . $document->getExtension());

        $tempFilePath = __DIR__ . '/../../../resources/' . $document->getOriginalName();
        file_put_contents($tempFilePath, $fileContent);

        $response = new BinaryFileResponse($tempFilePath);

        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();

        if ($mimeTypeGuesser->isGuesserSupported())
        {
            $response->headers->set('Content-Type', $mimeTypeGuesser->guessMimeType($tempFilePath));
        } else {
            $response->headers->set('Content-Type', 'text/plain');
        }

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $document->getOriginalName()
        );

        return $response;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO GET THE CONTENT OF A DOCUMENT BY URL
     * ES: SERVICIO PARA OBTENER EL CONTENIDO DE UN DOCUMENTO POR URL
     *
     * @param string $documentUrl
     * @return string|null
     */
    // --------------------------------------------------------------
    public function getContentOfDocumentByUrl(string $documentUrl): ?string
    {
        if ($this->filesystem->exists($this->storagePath . '/' . $documentUrl)) {
            return file_get_contents($this->storagePath . '/' . $documentUrl);
        } else {
            return null;
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO GET THE DOCUMENT URL
     * ES: SERVICIO PARA OBTENER LA URL DEL DOCUMENTO
     *
     * @param string $documentId
     * @return string
     */
    // --------------------------------------------------------------
    public function getDocumentUrl(string $documentId): string
    {

        $document = $this->documentRepository->findDocument($documentId);

        if ($this->filesystem->exists($this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' .$document->getExtension())) {
            return $this->storagePath . '/' . $document->getSubdirectory() . '/' . $document->getFileName() . '.' . $document->getExtension();
        } else {
            return $this->storagePath . '/images/no_image.png';
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: UPLOAD REQUEST
     * ES: SOLICITUD DE CARGA
     *
     * @param Request $request
     * @return JsonResponse
     */
    // --------------------------------------------------------------
    public function uploadRequest(Request $request): JsonResponse
    {
        $fileData = $request->files->get('image');

        $file = new UploadedFile(
            $fileData->getPathname(),
            $fileData->getFilename(),
            $fileData->getMimeType(),
            0,
            true
        );

        $document = $this->uploadDocument($file, $request->request->get('directory'));

        return new JsonResponse($this->generateUrl('document_render', ['subdomain'=> explode('.',$_SERVER["HTTP_HOST"])[0],'document' => $document->getId()]));
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A DOCUMENT
     * ES: SERVICIO PARA ELIMINAR UN DOCUMENTO
     *
     * @param Document $document
     * @return bool
     */
    // --------------------------------------------------------------
    public function deleteDocument(Document $document): bool
    {
        $this->documentRepository->deleteDocument($document);

        return true;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A DOCUMENT BY ID
     * ES: SERVICIO PARA OBTENER UN DOCUMENTO POR ID
     *
     * @param string $documentId
     * @param bool|null $array
     * @return Document|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function getDocumentById(string $documentId, ?bool $array = false): Document|array|null
    {
        return $this->documentRepository->findDocumentById($documentId, $array);
    }
    // --------------------------------------------------------------
}