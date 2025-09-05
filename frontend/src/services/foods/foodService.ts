import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';
import { CreateFoodFieldsModel, EditFoodFieldsModel } from '../../type/food-type';

const FOOD_ENDPOINT = '/food';
export class FoodService extends RestServiceConnection {

    /**
     * ------------------------------------------------------------------------
     * EN: FOOD REQUEST SERVICE
     * ES: SERVICIO DE ALIMENTOS
     * ------------------------------------------------------------------------
     */

    createFood = async (values: CreateFoodFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: FOOD_ENDPOINT + '/create-food',
            data: values,
        }, true);
        return this;
    }

    editFood = async (values: EditFoodFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: FOOD_ENDPOINT + '/edit-food',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    getFood = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: FOOD_ENDPOINT + '/list-food',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getFoodById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: FOOD_ENDPOINT + '/get-food',
            data: {
                foodId: id
            }
        }, true);
        return this;
    }

    deleteFood = async (foodId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: FOOD_ENDPOINT + '/delete-food',
            data: { foodId }
        }, true);
        return this;
    }
}