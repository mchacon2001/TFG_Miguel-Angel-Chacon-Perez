import { UserApiResponse, UsersApiResponse } from "./user-type";

export interface ApiResponse {
    success: boolean;
    message: string | null;
    data: unknown;
}

export interface Filters {
    filter_order:    any[] | any;
    filter_filters:   any[] | any;
    limit:          number;
    page:           number;
}

export interface CreatedAt {
    date:          Date;
    timezone_type: number;
    timezone:      string;
}

export interface UpdatedAt {
    date:          Date;
    timezone_type: number;
    timezone:      string;
}

export interface Permission {
    id:              number;
    action:          string;
    label:           string;
    description:     string;
    adminManaged:    boolean;
    moduleDependant: null;
}

export interface Role {
    id:          number;
    name:        string;
    description: string;
}

