import { ApiResponse, CreatedAt, Filters, Permission, Role } from "./apiResponse-type";

export type Users = User[] | null;
export type UsersError = Error | null;

export interface UsersApiResponse extends ApiResponse {
    totalRegisters: number;
    users:          Users;
    lastPage:       number;
    filters:        Filters;
}

export interface UserApiResponse extends ApiResponse {
    data: User | null;
}

export interface UserRole {
    id:      string;
    role:    Role;
}

export interface UserPermission {
    id:         string;
    permission: Permission;
}

export interface User {
    id:                 string;
    email:              string;
    name:               string;
    active:             boolean;
    profileImg:         ProfileImg | null;
    createdAt:          CreatedAt;
    userRoles:          UserRole[];
    userPermissions:    UserPermission[];
    documents:          any[];
}

export interface ProfileImg {
    id:                 string | null;
    originalName:       string;
    extension:          string;
    fileName:           string;
    subdirectory:       string;
    status:             boolean;
    createdAt:          CreatedAt;
    updatedAt:          CreatedAt | null;
}

export interface NewUser {
    email: string,
    password: string,
    name: string,
    role: string,
    last_name: string | undefined,
    profile_img? : File | undefined
}


export type EditUserFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    name: string;
    email: string;
    address: string;
    role: string | null;
    password: string;
    re_password: string;
    profile_img?: File;
}