import { AxiosResponse } from "axios";
import { RestServiceConnection } from "../restServiceConnection";

const DOCUMENT_TYPE_ENDPOINT = '/document-types';

export class DocumentTypeService extends RestServiceConnection {

    createDocumentType = async (documentType: DocumentType) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENT_TYPE_ENDPOINT + '/create-type',
            data: documentType
        }, true);
        return this;
    }

    getDocumentTypes = async (filters?: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENT_TYPE_ENDPOINT + '/list',
            data: filters ? filters :{
                "limit": 25,
                "page": 1,
                "filter_filters": {
                },
                "filter_order": [
                    {
                        "field": "id",
                        "order": "DESC"
                    }
                ]
            }
            }, true) as AxiosResponse;
        return this;
    }

    getDocumentTypeById = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENT_TYPE_ENDPOINT + '/get',
            data: {
                operation_type: id
            },
        }, true);
        return this;
    }

    deleteDocumentType = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENT_TYPE_ENDPOINT + '/remove-type',
            data: {
                documentType: id
            },
        }, true);
        return this;
    }

    getEntityTypes = async () => {
        this.response = await this.makeRequest({
            method: 'GET',
            url: DOCUMENT_TYPE_ENDPOINT + '/get-entity-types',
        }, true);
        return this;
    }
}