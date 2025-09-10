<?php
/**
 * Servicio para controlar y gestionar los filtros y orden de un listado
 *
 * @author Bartolomé Rojas Toledo
 */

namespace App\Utils\Tools;


use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\FilterRequest;
use Symfony\Component\HttpFoundation\Request;

class FilterService
{
    public array $order = [];
    public array $filters = [];
    public int $page = 1;
    public int $limit = 25;

    /**
     * Esta propiedad solo se rellenará en el caso de que el sistema de orden esté contenido en una variable única llamada current_request,
     * dentro de la petición que se realiza en el momento.
     * @var string
     *
     *
     */
    public $currentRequest = null;


    public function __construct(FilterRequest $request)
    {
        $this->order = @$request->get('filter_order') ?: [];
        $this->currentRequest = @$request->get('current_request') ?: null;
        $this->filters = @$request->get('filter_filters') ?: [];
        $this->page = @$request->get('page') ?: 1;
        $this->limit = @$request->get('limit') ?: 25;
    }

    /**
     * Devuelve el array con los parámtros de la query actual.
     * @return array
    */
    public function getAll(): array
    {
        return [
            "filter_order" => $this->order,
            "filter_filters" => $this->filters,
            "limit" => $this->limit,
            "page" => $this->page,
        ];
    }

    public function getAllOrders()
    {
        return $this->order;
    }

    private function addOrder($field, $order, &$currentRequest)
    {
        $exist = false;
        foreach ($currentRequest["filter_order"] as $index => $orderField) {
            if ($orderField["field"] == $field) {
                $exist = true;
                $currentRequest["filter_order"][$index]["order"] = $order;
            }
        }
        if (!$exist) {
            $currentRequest["filter_order"] = [];

            $currentRequest["filter_order"][] = [
                "field" => $field,
                "order" => $order
            ];
        }
    }

    public function addOrderValue($field, $order)
    {
        $this->order = [["field" => $field, 'order' => $order]];
    }

    public function getCurrentRequestParams(){
        return [
            "filter_order" => $this->order,
            "filter_filters" => $this->filters,
            "limit" => $this->limit,
            "page" => $this->page,
        ];
    }

    public function addFilter($filterName, $value) {
        $this->filters[$filterName] =  $value;
        return $this;
    }


    public function getCurrentRequest()
    {
        return $this->currentRequest;
    }

    /**
     * Recupera un order específico si existe en los ordenes establecidos
     * @param $fieldName
     * @return bool|array
     */
    public function getOrder($fieldName)
    {
        foreach ($this->order as $orderField) {
            if ($orderField["field"] == $fieldName) {
                return $orderField;
            }
        }
        return false;
    }


    /**
     * Comprueba si en la request existe este campo ordenado.
     * @param $fieldName
     * @return bool
     */
    public function isOrdered($fieldName)
    {
        $parameters = $this->getAll();
        if ($parameters and isset($parameters['filter_order']) and count($parameters['filter_order']) > 0) {
            foreach ($parameters['filter_order'] as $order) {
                if ($order["field"] == $fieldName) return true;
            }
        }
        return false;
    }

    /**
     * Comprueba si ya existe un orden para ese campo y retorna el orden contrario,
     * Se puede utilizar para mostrar los iconos ordenar en una dirección y otra.
     */
    public function getInversedOrder($fieldName)
    {
        $order = $this->getOrder($fieldName);
        if ($order) {
            if ($order["order"] == "desc") return "asc";
            if ($order["order"] == "asc") return "desc";
        }
        return "asc";
    }

    public function getFilters(){
        return $this->filters;
    }

    public function getOrders(){
        return $this->order;
    }


    /**
     * Coge los parámetros actuales de la petición y modifica el orden para
     * que se pueda generar un link con el orden cambiado
     * @param $fieldName
     * @param $order
     * @return array
     */
    public function futureOrderRequest($fieldName, $order){
        $currentRequest = $this->getCurrentRequestParams();
        $this->addOrder($fieldName,$order, $currentRequest);
        return $currentRequest;
    }

    public function filterField($fieldName){
        return "filter_filters[$fieldName]";
    }

    public function filterFormField(){
        return '<input type="hidden" name="current_request" value="'.$this->getCurrentRequest().'">';
    }


    /**
     * Recupera el valor de un campo filtrado
     * @param $fieldName
     * @return mixed
     */
    public function getFilterValue($fieldName){
        foreach ($this->filters as $indexName => $filter) {
            if($indexName == $fieldName) {
                return $filter;
            }
        }
        return null;
    }

}