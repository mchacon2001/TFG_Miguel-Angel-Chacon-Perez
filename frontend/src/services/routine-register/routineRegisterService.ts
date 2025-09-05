import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';
import { CreateRoutineFieldsModel, EditRoutineFieldsModel } from '../../type/routine-type';

const ROUTINE_REGISTER_ENDPOINT = '/routine-register';

export class RoutineRegisterService extends RestServiceConnection {

    getRoutineRegisterById = async (routineRegisterId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/get',
            data: { routineRegisterId }
        }, true);
        return this;
    }

    createRoutineRegister = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/create',
            data: values,
        }, true);
        return this;
    }

    editRoutineRegister = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/edit',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    listRoutineRegisters = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/list',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    deleteRoutineRegister = async (routineRegisterId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/delete',
            data: { routineRegisterId }
        }, true);

        return this;
    }
    
    finishRoutineRegister = async (routineRegisterId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/finish',
            data: { routineRegisterId }
        }, true);
        return this;
    }

    getActiveRoutineByUser = async (userId: string|undefined, routineId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_ENDPOINT + '/get-active-routine-by-user',
            data: { userId, routineId, day: 1 } // Add a default day parameter
        }, true);
        return this;
    }
}