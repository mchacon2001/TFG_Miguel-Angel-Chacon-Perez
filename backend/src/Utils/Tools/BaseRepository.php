<?php

namespace App\Utils\Tools;

use App\Utils\Exceptions\APIException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BaseRepository extends EntityRepository
{

    public const BASE_QUERY_PARAMS = [];

    /**
     * @throws APIException
     */
    public function getBaseQuery(): QueryBuilder
    {
        $params = self::BASE_QUERY_PARAMS;

        if(!empty($params)) {
            $query = $this->createQueryBuilder($params['alias']);
            if(!empty($params['joins'])) {
                foreach($params['joins'] as $join) {
                    $query->join($join['join'], $join['alias'], $join['conditionType'], $join['condition']);
                }
            }
            return $query;
        }

        throw new APIException('No se ha definido la constante BASE_QUERY_PARAMS en el repositorio ' . get_class($this) . ' para poder realizar la consulta base');
    }

}