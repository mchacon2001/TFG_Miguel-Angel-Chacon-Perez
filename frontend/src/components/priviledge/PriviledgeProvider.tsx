import React, { useContext, useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { RootState } from "../../redux/store";
import { AuthState } from "../../redux/authSlice";
import { JWTDecoded, Permissions } from "../../type/jwt-type";

type PrivilegeContextType = {
  permissions?: Permissions | null,
  roles?: string[],
  userCan: (action: string, group: string, superAdmin?: boolean) => boolean,
}

const PrivilegeContext: React.Context<PrivilegeContextType> = React.createContext<PrivilegeContextType>({
  permissions: {},
  roles: [],
  userCan: (action: string, group: string, superAdmin?: boolean): boolean => false,
});

type PrivilegeProviderProps = {
  children: React.ReactNode
}

const decode = (token: string) => {
  const base64Url = token.split('.')[1];
  const base64 = base64Url.replace('-', '+').replace('_', '/');
  return JSON.parse(window.atob(base64));
}

const PrivilegeProvider: React.FC<PrivilegeProviderProps> = ({ children }) => {
  const { user }: AuthState = useSelector((state: RootState) => state.auth);
  const [permissions, setPermissions] = useState<Permissions | undefined | null>(undefined);
  const [roles, setRoles] = useState<string[] | undefined>([]);

  useEffect(() => {
    resetState();
    if (user) {
      decodeToken();
    } else {
      setPermissions(null);
    }
  }, [user]);

  const resetState = () => {
    setPermissions(undefined);
    setRoles(undefined);
  }

  const decodeToken = async () => {
    if (user !== null) {
      const decoded: JWTDecoded = decode(user?.token as string);
      setRoles(decoded.roles)
      setPermissions(decoded.permissions);
    }
  }

  const userCan = (action: string, group: string, superAdmin?: boolean) => {
    if (permissions === undefined || permissions === null) {
      return false;
    }

    if (superAdmin && !roles?.includes("Superadministrador")) {
      return false;
    }

    if (permissions[group] && permissions[group].includes(action)) {
      return true;
    }

    return false;
  }

  const value: PrivilegeContextType = {
    permissions: permissions,
    roles: roles,
    userCan
  }

  return (
    <>
      {permissions !== undefined
        ? <PrivilegeContext.Provider value={value}>{children}</PrivilegeContext.Provider>
        : <></>
      }
    </>
  )
}

export { PrivilegeProvider, PrivilegeContext }

export function usePrivilege() {
  return useContext(PrivilegeContext);
}