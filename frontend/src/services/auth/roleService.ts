import { NewRole } from "../../type/role-type";
import { RestServiceConnection } from "../restServiceConnection";

const ROLES_ENDPOINT = "/roles";

export class RoleService extends RestServiceConnection {

  getRoles = async (filters?: any) => {
    this.response = await this.makeRequest({
      method: 'POST',
      url: ROLES_ENDPOINT + '/list',
      data: filters ? {...filters} : {},
    }, true);
    return this;
  };

  getRoleById = async (id: string) => {
    this.response = await this.makeRequest({
        method: 'POST',
        url: ROLES_ENDPOINT + '/get',
        data: {
            roleId: id
        },
        headers: {
            "Content-Type": "application/json"
        }
    }, true);
    return this;
}
 
  createRole = async (role: NewRole) => {
    this.response = await this.makeRequest(
      {
        method: "POST",
        url: ROLES_ENDPOINT + "/create",
        data: role,
      },
      true
    );
    return this;
  };

  editRole = async (roleData: any) => {

    this.response = await this.makeRequest({
        method: 'POST',
        url: ROLES_ENDPOINT + '/edit',
        data: {
          roleId: roleData.roleId,
          name: roleData.name,
          description: roleData.description,
          permissions: roleData.permissions,
        },
    }, true);
    return this;
  };

  listRoles = async (filters?: any) => {
    this.response = await this.makeRequest(
      {
        method: "POST",
        url: ROLES_ENDPOINT + "/list",
        data: filters,
      },
      true
    );
    return this;
  };

  deleteRole = async (id: string) => {
    this.response = await this.makeRequest(
      {
        method: "POST",
        url: ROLES_ENDPOINT + "/delete",
        data: {roleId: id},
      },
      true
    );
    return this;
  };

  toggleRoleStatus = async (id: string) => {
    this.response = await this.makeRequest({
      method: 'POST',
      url: ROLES_ENDPOINT + '/toggle',
      data: {
        roleId: id
      }
    }, true);
    return this;
  }
}
