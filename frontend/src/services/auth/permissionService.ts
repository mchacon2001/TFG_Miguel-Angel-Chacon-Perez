import { RestServiceConnection } from "../restServiceConnection";

const PERMISSIONS_ENDPOINT = "/permissions";

export class PermissionService extends RestServiceConnection {
  getPermissions = async () => {
    this.response = await this.makeRequest(
      {
        method: "POST",
        url: PERMISSIONS_ENDPOINT + "/get-all",
      },
      true
    );
    return this;
  };
}
