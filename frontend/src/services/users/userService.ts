import { AxiosResponse } from 'axios';
import { RestServiceConnection } from '../restServiceConnection';
import { EditUserFieldsModel } from '../../type/user-type';

const USER_ENDPOINT = '/users';
export class UserService extends RestServiceConnection {

    /**
     * Posting to API Form Data type in order to send the user photo
     */
    createUser = async (values: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/create',
            data: values,
        }, true);
        return this;
    }

    editUser = async (values: EditUserFieldsModel) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/edit',
            data: values,
            headers: {
                "Content-Type": "application/json"
            }
        }, true);
        return this;
    }

    getUsers = async (filters?: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/list',
            data: {
                ...filters
            },
        }, true) as AxiosResponse;
        return this;
    }

    getUserById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/get',
            data: {
                userId: id
            }
        }, true);
        return this;
    }

    getUserRoles = async (filters?: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/list-user-roles',
            data: {
                ...filters
            },
        }, true);
        return this;
    }

    getDocuments = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/get-documents',
            data: {
                user: id
            }
        }, true);
        return this;
    }

    getDocumentsByDocumentType = async (id: string, documentType: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/get-documents-by-documentType',
            data: {
                user: id,
                documentType: documentType
            }
        }, true);
        return this;
    }

    toggleUserStatus = async (id: string, status: boolean) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/toggle',
            data: {
                userId: id,
            }
        }, true);
        return this;
    }

    loginLikeUser = async (userId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/login-like-user',
            data: {
                userId: userId,
            }
        }, true);
        return this;
    }

    addUserDocument = async (data: FormData) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/add-document',
            data: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }, true);
        return this;
    }

    addUserDocuments = async (data: FormData) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/add-documents',
            data: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        }, true);
        return this;
    }

    editUserPermissions = async (user: string, permissions: number[]) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/edit-permissions',
            data: {
                userId: user,
                permissions: permissions
            }
        }, true);
        return this;
    }

    restoreUserPermissions = async (user: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/reset-permissions',
            data: {
                userId: user,
            }
        }, true);
        return this;
    }

    updateImage = async (file: File, userId: string) => {
        let formData = new FormData();
        formData.append('profileImg', file);
        formData.append('userId', userId);

        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/add-image',
            headers: {
                "Content-Type": "multipart/form-data"
            },
            data: formData
        }, true);

        return this
    }

    deleteProfileImage = async (userId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/delete-image',
            data: {
                userId: userId
            }
        }, true);

        return this
    }

    changeUserPassword = async (userId: string, password: string, passwordConfirm: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/change-password',
            data: {
                userId: userId,
                password: password,
                passwordConfirm: passwordConfirm
            }
        }, true);

        return this
    }

    deleteUser = async (userId: string) => {

        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/delete',
            data: {
                userId: userId
            }
        }, true);

        return this
    }

    me = async () => {
        this.response = await this.makeRequest({
            method: 'GET',
            url: USER_ENDPOINT + '/me',
        }, true);

        return this
    }

    addPhysicalStats = async (userId: string, data: { height: number, weight: number }) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/add-physical-stats',
            data: {
                userId: userId,
                ...data
            }
        }, true);

        return this;
    }
    addMentalStats = async (userId: string, data: { mood: number, sleepQuality: number}) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/add-mental-stats',
            data: {
                userId: userId,
                ...data
            }
        }, true);

        return this;
    }

    getPhysicalStats = async (userId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/get-physical-stats',
            data: {
                userId: userId
            }
        }, true);

        return this;
    }
    getMentalStats = async (userId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/get-mental-stats',
            data: {
                userId: userId
            }
        }, true);

        return this;
    }
    getCalorieIntake = async (userId: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: USER_ENDPOINT + '/get-calorie-intake',
            data: {
                userId: userId
            }
        }, true);

        return this;
    }
}