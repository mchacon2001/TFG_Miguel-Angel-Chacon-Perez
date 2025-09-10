import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { FilterOptions } from '../../hooks/useFilters';
import { CreateRoutineFieldsModel, EditRoutineFieldsModel } from '../../type/routine-type';

const ROUTINE_ENDPOINT = '/user-has-routines';

export class UserHasRoutineService extends RestServiceConnection {


    createUserHasRoutine = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/create',
            data: values,
        }, true);
        return this;
    }

    editUserHasRoutine = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/edit',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    listUserHasRoutines = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/list',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }


    deleteUserHasRoutine = async (routineId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: ROUTINE_ENDPOINT + '/delete',
            data: { userHasRoutineId: routineId }
        }, true);

        return this
    }

}