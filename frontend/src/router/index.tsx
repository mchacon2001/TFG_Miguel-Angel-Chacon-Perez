import {Navigate, Route, Routes} from 'react-router-dom';
import Login from '../pages/auth/Login';
import Page404 from '../pages/auth/Page404';
import contents from './routes';
import ProtectedRoute from './ProtectedRoute';
import AppLayout from '../pages/_layout/AppLayout';
import {useSelector} from "react-redux";
import {RootState} from "../redux/store";
import LoginForgotPassword from '../pages/auth/ForgotPassword/ForgotPassword';
import ResetPassword from '../pages/auth/ResetPassword/ResetPassword';
import Register from '../pages/auth/Register/Register';
import { useContext } from 'react';
import { PrivilegeContext } from '../components/priviledge/PriviledgeProvider';

const AppRouter = () => {

  const { isAuthenticated } = useSelector((state: RootState) => state.auth);
  const { userCan } = useContext(PrivilegeContext);

  const renderRoute = (page: any, index: number) => {

    page.element = <ProtectedRoute component={page.element} access={page.access}/>;

    return (
      <Route key={index} {...page}>
        {page.sub?.map((subPage: any, index: number) =>
          renderRoute(subPage, index)
        )}
      </Route>
    );

  };

  return (
      <Routes>
         <Route element={<AppLayout/>}>
          {contents.map((page, index) => renderRoute(page, index))}
        </Route> 
        <Route path="/login" element={<Login/>}/>
        <Route path="/forgot-password" element={<LoginForgotPassword/>}/>
        <Route path="/reset-password" element={<ResetPassword/>}/>
        <Route path="/register" element={<Register/>}/>
        {!isAuthenticated && <Route path="*" element={<Navigate to={"/login"}/>}/>}
        {isAuthenticated && <Route path="*" element={<Page404/>}/>}
        {isAuthenticated && (userCan('admin_routines', 'routines') ? <Route path="/" element={<Navigate to={"/routines"}/>}/> : <Route path="/" element={<Navigate to={"/user-routines"}/>}/>)}
      </Routes>
  );
};

export default AppRouter;