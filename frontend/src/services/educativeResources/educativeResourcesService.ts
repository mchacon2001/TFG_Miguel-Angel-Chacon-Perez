import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';
import { CreateFoodFieldsModel, EditFoodFieldsModel } from '../../type/food-type';

const EDUCATIVE_RESOURCES_ENDPOINT = '/educative-resources';
export class EducativeResourceService extends RestServiceConnection {

    /**
     * ------------------------------------------------------------------------
     * EN: EDUCATIVE RESOURCES REQUEST SERVICE
     * ES: SERVICIO DE RECURSOS EDUCATIVOS
     * ------------------------------------------------------------------------
     */

    createEducativeResource = async (values: CreateFoodFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EDUCATIVE_RESOURCES_ENDPOINT + '/create-educative-resource',
            data: values,
        }, true);
        return this;
    }

    editEducativeResource = async (values: EditFoodFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EDUCATIVE_RESOURCES_ENDPOINT + '/edit-educative-resource',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    getEducativeResource = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EDUCATIVE_RESOURCES_ENDPOINT + '/list-educative-resources',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getEducativeResourceById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EDUCATIVE_RESOURCES_ENDPOINT + '/get-educative-resource',
            data: {
                educativeResourceId: id
            }
        }, true);
        return this;
    }

    deleteEducativeResource = async (educativeResourceId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EDUCATIVE_RESOURCES_ENDPOINT + '/delete-educative-resource',
            data: { educativeResourceId }
        }, true);
        return this;
    }
}