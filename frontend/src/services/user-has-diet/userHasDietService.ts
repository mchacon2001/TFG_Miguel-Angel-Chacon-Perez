import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';

const USER_HAS_DIET_ENDPOINT = '/user-has-diet';

export class UserHasDietService extends RestServiceConnection {

    /**
     * ------------------------------------------------------------------------
     * EN: USER HAS DIET REQUEST SERVICE
     * ES: SERVICIO DE USUARIO TIENE DIETA
     * ------------------------------------------------------------------------
     */

    createUserHasDiet = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/create',
            data: values,
        }, true);
        return this;
    }

    editUserHasDiet = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/edit',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    listUserHasDiets = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/list',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    deleteUserHasDiet = async (dietId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/delete',
            data: { userHasDietId: dietId }
        }, true);

        return this
    }

    toogleUserHasDiet = async (dietId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/toggle',
            data: { userHasDietId: dietId }
        }, true);

        return this;
    }

    createDailyIntake = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/daily-intake/create',
            data: values,
        }, true);
        return this;
    }

    getDailyIntake = async (values: { userId: string, date: string }) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_HAS_DIET_ENDPOINT + '/daily-intake/get',
            data: values,
        }, true);
        return this;
    }
}
