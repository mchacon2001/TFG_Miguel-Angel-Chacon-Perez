<?php

namespace App\Services\EducativeResource;

use App\Entity\EducativeResources\EducativeResources;
use App\Repository\EducativeResource\EducativeResourceRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EducativeResourcesService
{
    /**
     * @var EducativeResourceRepository|EntityRepository
     */
    protected EducativeResourceRepository|EntityRepository $educativeResourceRepository;

    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
    )
    {
        $this->educativeResourceRepository = $em->getRepository(EducativeResources::class);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EDUCATIVE RESOURCE SERVICES
    // ES: SERVICIOS DE RECURSOS EDUCATIVOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET EDUCATIVE RESOURCE BY ID
     * ES: SERVICIO PARA OBTENER UN RECURSO EDUCATIVO POR ID
     *
     * @param string $educativeResourceId
     * @param bool $array
     * @return EducativeResources|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getEducativeResourceById(string $educativeResourceId, ?bool $array = false): null|EducativeResources|array
    {
        return $this->educativeResourceRepository->findById($educativeResourceId, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET AN EDUCATIVE RESOURCE BY NAME
     * ES: SERVICIO PARA OBTENER UN RECURSO EDUCATIVO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return EducativeResources|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getEducativeResourceByName(string $name, ?bool $array = false): EducativeResources|array|null
    {
        return $this->educativeResourceRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST EDUCATIVE RESOURCES CATEGORIES
     * ES: SERVICIO PARA LISTAR LAS CATEGORIAS DE RECURSOS EDUCATIVOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listEducativeResourcesService(FilterService $filterService): array
    {
        return $this->educativeResourceRepository->list($filterService);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE AN EDUCATIVE RESOURCE
     * ES: SERVICIO PARA CREAR UN RECURSO EDUCATIVO
     *
     * @param string $title
     * @param string $youtubeUrl
     * @param string|null $description
     * @param bool $isVideo
     * @param string $tag
     * @return EducativeResources|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function createEducativeResourceService(
        string $title,
        string $youtubeUrl,
        ?string $description = null,
        bool $isVideo,
        string $tag
    ): EducativeResources|null
    {
        return $this->educativeResourceRepository->create(
            title: $title,
            youtubeUrl: $youtubeUrl,
            description: $description,
            isVideo: $isVideo,
            tag: $tag
        );
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT AN EDUCATIVE RESOURCE
     * ES: SERVICIO PARA EDITAR UN RECURSO EDUCATIVO
     *
     * @param EducativeResources $educativeResource
     * @param string $title
     * @param string|null $youtubeUrl
     * @param string|null $description
     * @param bool $isVideo
     * @param string $tag
     * @return EducativeResources|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function editEducativeResourceService(
        EducativeResources $educativeResource,
        string $title,
        ?string $youtubeUrl = null,
        ?string $description = null,
        bool $isVideo,
        string $tag
    ): EducativeResources|null
    {
        $educativeResourceEdited = $this->educativeResourceRepository->edit(
            educativeResource: $educativeResource,
            title: $title,
            youtubeUrl: $youtubeUrl,
            description: $description,
            isVideo: $isVideo,
            tag: $tag
        );

        return $educativeResourceEdited;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE AN EDUCATIVE RESOURCE
     * ES: SERVICIO PARA ELIMINAR UN RECURSO EDUCATIVO
     *
     * @param EducativeResources $educativeResource
     * @return EducativeResources|null
     */
    // ------------------------------------------------------------------------
    public function deleteEducativeResourceService(EducativeResources $educativeResource): EducativeResources|null
    {
        return $this->educativeResourceRepository->remove($educativeResource);
    }
    // ------------------------------------------------------------------------

}