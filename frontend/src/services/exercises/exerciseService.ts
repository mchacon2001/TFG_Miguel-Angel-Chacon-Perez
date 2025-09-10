import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { EditExerciseFieldsModel, CreateExerciseFieldsModel } from '../../type/exercise-type';
import { FilterOptions } from '../../hooks/useFilters';

const EXERCISE_ENDPOINT = '/exercises';
export class ExerciseService extends RestServiceConnection {

    /**
     * ------------------------------------------------------------------------
     * EN: EXERCISE REQUEST SERVICE
     * ES: SERVICIO DE EJERCICIOS
     * ------------------------------------------------------------------------
     */

    createExercise = async (values: CreateExerciseFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/create-exercise',
            data: values,
        }, true);
        return this;
    }

    editExercise = async (values: EditExerciseFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/edit-exercise',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    getExercises = async (filters?: FilterOptions) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/list-exercises',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getExerciseById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/get-exercise',
            data: {
                exerciseId: id
            }
        }, true);
        return this;
    }

    deleteExercise = async (userId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/delete-exercise',
            data: { exerciseId: userId }
        }, true);
        return this;
    }

    /**
     * ------------------------------------------------------------------------
     * EN: EXERCISE CATEGORIES REQUEST
     * ES: SERVICIO DE CATEGORIAS DE EJERCICIOS
     * ------------------------------------------------------------------------
     */

    getExercisesCategories = async (filters?: FilterOptions | any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/list-exercise-categories',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getCategoryById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/get-category',
            data: {
                exerciseCategoryId: id
            }
        }, true);
        return this;
    }

    createExerciseCategory = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/create-category',
            data: values,
        }, true);
        return this;
    }

    editExerciseCategory = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/edit-category',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    deleteExerciseCategory = async (exerciseCategoryId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: EXERCISE_ENDPOINT + '/delete-category',
            data: { exerciseCategoryId }
        }, true);
        return this;
    }
}