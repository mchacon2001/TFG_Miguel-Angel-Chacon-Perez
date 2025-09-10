import { ApiResponse, Filters} from "./apiResponse-type";

export type DocumentTypes = DocumentType[] | null;
export type DocumentTypeError = Error | null;

export interface DocumentTypesApiResponse extends ApiResponse {
    totalRegisters: number;
    documentTypes: DocumentTypes;
    lastPage: number;
    filters: Filters;
}

export interface DocumentTypeApiResponse extends ApiResponse {
    data: DocumentType | null;
}

export interface DocumentType {
    id: number,
    name: string,
    description: string,
    requiredDocument: boolean,
    entityType: string,
}

export interface NewDocumentType {
    name: string,
    description: string,
    requiredDocument: boolean,
    entittyType: string
}