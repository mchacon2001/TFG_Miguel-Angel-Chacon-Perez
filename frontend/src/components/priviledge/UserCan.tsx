import React, {FC, useContext} from "react";
import {PrivilegeContext} from "./PriviledgeProvider";

type IUserCanProps = {
  children: React.ReactNode;
  action: string;
  group: string;
  superAdmin?: boolean;
}

export const UserCan: FC<IUserCanProps> = ({ children, action, group, superAdmin }) => {

  const { userCan } = useContext(PrivilegeContext);

  return (<> {userCan(action, group, superAdmin) && children} </>)
}