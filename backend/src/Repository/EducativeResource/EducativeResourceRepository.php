<?php

namespace App\Repository\EducativeResource;

use App\Entity\EducativeResources\EducativeResources;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EducativeResourceRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A EDUCATIVE RESOURCE BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN RECURSO EDUCATIVO POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return EducativeResources|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|EducativeResources|array
    {
        return $this->createQueryBuilder('er')
            ->andWhere('er.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A EDUCATIVE RESOURCE BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UN RECURSO EDUCATIVO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return EducativeResources|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $title, ?bool $array = false): null|EducativeResources|array
    {
        return $this->createQueryBuilder('er')
            ->andWhere('er.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST EDUCATIVE RESOURCES
     * ES: FUNCIÓN PARA LISTAR RECURSOS EDUCATIVOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('er');

        $this->setFilters($query, $filterService);
        $this->setOrders($query, $filterService);

        $query->setFirstResult($filterService->page > 1 ? (($filterService->page - 1)*$filterService->limit) : $filterService->page - 1);
        $query->setMaxResults($filterService->limit);

        $paginator = new Paginator($query);
        $paginator->getQuery()->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);
        $totalRegisters = $paginator->count();

        $result = [];

        foreach ($paginator as $verification) {
            $result[] = $verification;
        }

        $lastPage = (integer)ceil($totalRegisters / $filterService->limit);

        return [
            'totalRegisters' => $totalRegisters,
            'educativeResources' => $result,
            'lastPage'       => $lastPage,
            'filters'        => $filterService->getAll()
        ];
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO SET FILTERS
     * ES: FUNCIÓN PARA ESTABLECER FILTROS
     *
     * @param QueryBuilder $query
     * @param FilterService $filterService
     * @return void
     */
    // --------------------------------------------------------------
    public function setFilters(QueryBuilder $query, FilterService $filterService): void
    {
        if (count($filterService->getFilters()) > 0)
        {
            $search_array = $filterService->getFilterValue('search_array');
            if ($search_array != null)
            {
                $array_values = explode(' ', $search_array);

                $conditions = [];
                $parameters = [];

                foreach ($array_values as $index => $value)
                {
                    $param = 'search' . $index;
                    $conditions[] = 'er.title LIKE :' . $param;
                    $parameters[$param] = '%' . $value . '%';
                }

                if (!empty($conditions))
                {
                    $query->andWhere(implode(' AND ', $conditions));

                    foreach($parameters as $key => $value)
                    {
                        $query->setParameter($key, $value);
                    }
                }
            }

            $educativeResource = $filterService->getFilterValue('id');
            if($educativeResource != null)
            {
                $query->andWhere('er.id = :id')
                ->setParameter('id', $educativeResource);

            }

            $title = $filterService->getFilterValue('title');
            if($title != null)
            {
                $query->andWhere('er.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
            }   

            $tag = $filterService->getFilterValue('tag');
            if($tag != null)
            {
                $query->andWhere('er.tag LIKE :tag')
                ->setParameter('tag', '%' . $tag . '%');
            }
            $isVideo = $filterService->getFilterValue('isVideo');
            if($isVideo != null)
            {
                $query->andWhere('er.isVideo = :isVideo')
                ->setParameter('isVideo', $isVideo);
            }

    }
}
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A EDUCATIVE RESOURCE ITEM
     * ES: FUNCIÓN PARA CREAR UN RECURSO EDUCATIVO
     *
     * @param string $title
     * @param string $youtubeUrl
     * @param string|null $description
     * 
     * @return EducativeResources|null
     */
    // --------------------------------------------------------------
public function create(
    string $title,
    string $youtubeUrl,
    ?string $description,
    bool $isVideo,
    string $tag
): EducativeResources|null {
    $educativeResource = (new EducativeResources())
        ->setTitle($title)
        ->setYoutubeUrl($youtubeUrl)
        ->setDescription($description)
        ->setIsVideo($isVideo)
        ->setTag($tag);
    $educativeResource->setCreatedAt(new DateTime());

    $this->save($this->_em, $educativeResource);

    return $educativeResource;
}

    // --------------------------------------------------------------


    // -------------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT A EDUCATIVE RESOURCE ITEM
     * ES: FUNCIÓN PARA EDITAR UN RECURSO EDUCATIVO
     *
     * @param EducativeResources $educativeResource
     * @param string $title
     * @param string|null $youtubeUrl
     * @param string|null $description
     * @return EducativeResources|null
     */
    // --------------------------------------------------------------
    public function edit(
        EducativeResources $educativeResource,
        string $title,
        ?string $youtubeUrl,
        ?string $description,
        bool $isVideo,
        string $tag
    ): EducativeResources|null {
        $educativeResource->setTitle($title);
        $educativeResource->setYoutubeUrl($youtubeUrl);
        $educativeResource->setDescription($description);
        $educativeResource->setIsVideo($isVideo);
        $educativeResource->setTag($tag);

        $this->save($this->_em, $educativeResource);

        return $educativeResource;
    }
    // ----------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE A EDUCATIVE RESOURCE ITEM
     * ES: FUNCIÓN PARA ELIMINAR UN RECURSO EDUCATIVO
     *
     * @param EducativeResources $educativeResource
     * @return null
     */
    // --------------------------------------------------------------
    public function remove(
        EducativeResources $educativeResource
    ): null
    {
        $this->delete($this->_em, $educativeResource);

        return null;
    }
    // --------------------------------------------------------------

        // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO SET ORDER
     * ES: FUNCIÓN PARA ESTABLECER ORDEN
     *
     * @param QueryBuilder $query
     * @param FilterService $filterService
     * @return void
     */
    // --------------------------------------------------------------
    public function setOrders(QueryBuilder $query, FilterService $filterService): void
    {
        if (count($filterService->getOrders()) > 0) {
            foreach ($filterService->getOrders() as $order)
            {
                switch ($order['field'])
                {
                    case "id":
                        $query->orderBy('er.id', $order['order']);
                        break;
                    case "title":
                        $query->orderBy('er.title', $order['order']);
                        break;
                    case "youtube_url":
                        $query->orderBy('er.youtubeUrl', $order['order']);
                        break;
                    case "description":
                        $query->orderBy('er.description', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('er.createdAt', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('er.createdAt', 'DESC');
        }
    }
    // --------------------------------------------------------------

}