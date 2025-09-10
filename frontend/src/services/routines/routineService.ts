import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';
import { CreateRoutineFieldsModel, EditRoutineFieldsModel } from '../../type/routine-type';

const ROUTINE_ENDPOINT = '/routines';

export class RoutineService extends RestServiceConnection {

    /**
     * ------------------------------------------------------------------------
     * EN: ROUTINE REQUEST SERVICE
     * ES: SERVICIO DE RUTINAS
     * ------------------------------------------------------------------------
     */

    createRoutine = async (values: CreateRoutineFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/create-routine',
            data: values,
        }, true);
        return this;
    }

    editRoutine = async (values: EditRoutineFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/edit-routine',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    getRoutines = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/list-routines',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getRoutineWithDays = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/get-routine-with-days',
            data: {
                routineId: id
            }
        }, true);
        return this;
    }

    getRoutineForEdit = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/get-routine-for-edit',
            data: {
                routineId: id
            }
        }, true);
        return this;
    }

    getRoutineById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/get-routine',
            data: {
                routineId: id
            }
        }, true);
        return this;
    }

    deleteRoutine = async (routineId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/delete-routine',
            data: { routineId }
        }, true);

        return this
    }

    toggleRoutineStatus = async (routineId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/toggle-routine',
            data: { routineId }
        }, true);

        return this
    }

    exportRoutines = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/export-routines',
            responseType: 'arraybuffer',
            data: { ...filters },
        }, true) as AxiosResponse;
        return this;
    }

    listRoutineExercises = async (filters?: FilterOptions | any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/list-routine-exercises',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }
    
    assignUserToRoutine = async (routineId: string, userIds: string[]) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/assign-user',
            data: {
                routineId,
                userIds
            }
        }, true);
        return this;
    }
    /**
     * ------------------------------------------------------------------------
     * EN: ROUTINE CATEGORIES REQUEST
     * ES: SERVICIO DE CATEGORIAS DE RUTINAS
     * ------------------------------------------------------------------------
     */

    getRoutineCategories = async (filters?: FilterOptions | any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/list-routine-categories',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getCategoryById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/get-routine-category',
            data: {
                routineCategoryId: id
            }
        }, true);
        return this;
    }

    createRoutineCategory = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/create-routine-category',
            data: values,
        }, true);
        return this;
    }

    editRoutineCategory = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/edit-routine-category',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    deleteRoutineCategory = async (routineCategoryId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/delete-routine-category',
            data: { routineCategoryId }
        }, true);
        return this;
    }

}