import { useCallback, useEffect, useState } from 'react';
import { RoleService } from '../services/auth/roleService';
import { RolesApiResponse } from '../type/role-type';
import { userIsSuperAdmin } from '../utils/userIsSuperAdmin';

//------------------------------------------------------------------------
/**
* 
* EN: Hook to manage roles fetching and transformation logic.
* ES: Hook para gestionar la lógica de obtención y transformación de roles.
*
* @returns 
*/
//----------------------------------------------------------------------
const useRoles = () => {
  const [roles, setRoles] = useState<any>([]);

  const fetchRoles = useCallback(async () => {
    try {
      const roleService = new RoleService();
      const response = await roleService.listRoles();
      const fetchedRolesData = response.getResponseData() as RolesApiResponse;

      if (fetchedRolesData && fetchedRolesData.data.roles) {
        const mappedRoles = fetchedRolesData.data.roles.map((role: { id: any; name: any; }) => ({
            value: role.id,
            label: role.name
        }));
        setRoles(mappedRoles);
      }

    } catch (error) {
      console.log('Error fetching roles:', error);
    }
  }, []);

  useEffect(() => {
    fetchRoles();
  },[])

  const getRolesList = () => {
    return roles;
  }

  return { roles, fetchRoles, getRolesList };
}

export default useRoles;