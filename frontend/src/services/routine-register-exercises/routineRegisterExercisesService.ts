import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';

const ROUTINE_REGISTER_EXERCISES_ENDPOINT = '/routine-register-exercises';

export class RoutineRegisterExercisesService extends RestServiceConnection {

    getRoutineRegisterExercisesById = async (routineRegisterExercisesId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_EXERCISES_ENDPOINT + '/get',
            data: { routineRegisterExercisesId }
        }, true);
        return this;
    }

    createRoutineRegisterExercises = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_EXERCISES_ENDPOINT + '/create',
            data: values,
        }, true);
        return this;
    }

    editRoutineRegisterExercises = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_EXERCISES_ENDPOINT + '/edit',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    listRoutineRegisterExercises = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_EXERCISES_ENDPOINT + '/list',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    deleteRoutineRegisterExercises = async (routineRegisterExercisesId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_REGISTER_EXERCISES_ENDPOINT + '/delete',
            data: { routineRegisterExercisesId }
        }, true);

        return this;
    }
}