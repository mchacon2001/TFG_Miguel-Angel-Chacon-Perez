import { ApiResponse, CreatedAt, Filters } from "./apiResponse-type";

export type Roles = Role[];
export type Permissions = Permission[] | null;
export type RoleError = Error | null;

export interface RolesApiResponse extends ApiResponse {
    data: {roles: Roles}
}

export interface PermissionsApiResponse extends ApiResponse {
    data: Permissions
}

export interface Permission {
    id:         number;
    action:     string;
    label:      string;
    description:string;
    adminManaged: boolean;
}

export interface PermissionGroup {
    id: number;
    label: string;
    name: string;
    permissions: Permission[];
}

export interface RolePermission {
    id: string;
    permission: Permission;
}

export interface Role {
    id: number;
    name: string;
    description: string;
    inmutable?: boolean;
    permissions?: RolePermission[];
}

export interface NewRole {
    name: string;
    description: string;
    permissions: number[];
}
