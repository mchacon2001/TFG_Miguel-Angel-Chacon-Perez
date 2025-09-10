import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';
import { CreateDietFieldsModel, EditDietFieldsModel } from '../../type/diet-type';

const DIET_ENDPOINT = '/diet';

export class DietService extends RestServiceConnection {

    /**
     * ------------------------------------------------------------------------
     * EN: DIET REQUEST SERVICE
     * ES: SERVICIO DE DIETAS
     * ------------------------------------------------------------------------
     */

    createDiet = async (values: CreateDietFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/create-diet',
            data: values,
        }, true);
        return this;
    }

    editDiet = async (values: EditDietFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/edit-diet',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    getDiets = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/list-diets',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getDietById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/get-diet',
            data: {
                dietId: id
            }
        }, true);
        return this;
    }

    deleteDiet = async (dietId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/delete-diet',
            data: { dietId }
        }, true);

        return this
    }

    getDietForEdit = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/get-diet-for-edit',
            data: {
                dietId: id
            }
        }, true);
        return this;
    }

    getDietWithDays = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/get-diet-with-days',
            data: {
                dietId: id
            }
        }, true);
        return this;
    }

    assignUserToDiet = async (dietId: string, userIds: string[]) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DIET_ENDPOINT + '/assign-user',
            data: {
                dietId,
                userIds
            }
        }, true);
        return this;
    }
}