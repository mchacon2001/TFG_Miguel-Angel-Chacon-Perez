import React, { useContext } from 'react';
import { useSelector } from 'react-redux';
import {  Navigate } from 'react-router-dom';
import { RootState } from '../redux/store';
import { PrivilegeContext } from '../components/priviledge/PriviledgeProvider';
import ErrorPageComponent from '../pages/extra/ErrorPageComponent';

interface ProtectedRouteProps {
  component: React.ComponentType;
  access: any|undefined;
}

const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ component, access }) => {

  const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);
  const {userCan} = useContext(PrivilegeContext)

  const errorAccessPage = <ErrorPageComponent error={"No tienes acceso a este recurso"}/>

  const determineAccess = () => {

    if(access !== undefined && !userCan(access.action, access.group)) {
      return errorAccessPage;
    }

    return (component);
  }

  return (
    <>
      {
        (user && isAuthenticated) ? determineAccess() : null
      }
      {
        (user && !isAuthenticated) ? <Navigate to="/" /> : null
      }
      {
        (!user && !isAuthenticated) ? <Navigate to="/login" /> : null
      }
    </>
  )

};

export default ProtectedRoute;
