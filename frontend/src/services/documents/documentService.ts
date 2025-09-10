
import { RestServiceConnection } from '../restServiceConnection';

const DOCUMENTS_ENDPOINT = '/documents';

export class DocumentService extends RestServiceConnection {
    
    getDocument = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENTS_ENDPOINT + '/get',
            data: {
                document: id
            },
        }, true);
        return this;
    }

    renderDocument = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            responseType: 'blob',
            url: DOCUMENTS_ENDPOINT + '/render',
            data: {
                document: id
            },
            headers: {
                "Content-Type": "application/json",
                "responseType": "blob"
            }
        }, true);
        return this;
    }

    deleteDocument = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENTS_ENDPOINT + '/delete',
            data: {
                document: id
            },
        }, true);
        return this;
    }

    deleteDocuments = async (documents: any) => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: DOCUMENTS_ENDPOINT + '/delete-documents',
            data: {
                documents: documents
            },
        }, true);
        return this;
    }

    downloadDocument = async (id: string) => {
        this.response = await this.makeRequest({
            method: 'POST',
            responseType: 'blob',
            url: DOCUMENTS_ENDPOINT + '/render',
            data: {
                document: id
            },
            headers: {
                "Content-Type": "application/json",
                "responseType": "blob"
            }
        }, true);
        return this;
    }

    renderDocumentURL = (id?: string) => {
        if(!id) return '';
        return `${process.env.REACT_APP_API_URL}${DOCUMENTS_ENDPOINT}/render-document/${id}`
    }
}