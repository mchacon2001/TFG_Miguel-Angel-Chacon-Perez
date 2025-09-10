import { ApiResponse, Filters} from "./apiResponse-type";

export type Services = Service[] | null;
export type ProductError = Error | null;

export interface ServicesApiResponse extends ApiResponse {
    totalRegisters: number;
    services: Services;
    lastPage: number;
    filters: Filters;
}

export interface ServiceApiResponse extends ApiResponse {
    data: Service | null;
}

export interface Service {
    id: string,
    business: string,
    name: string,
    description?: string,
}

export interface UpdateService {
    business: string,
    service: string,
    name: string,
    description?: string,
}

export type NewService = Omit<Service, "id">;
