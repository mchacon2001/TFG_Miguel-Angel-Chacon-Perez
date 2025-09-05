import { FC, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import Alert from '../../../components/bootstrap/Alert';
import { LoginService } from '../../../services/auth/loginService';
import { useSelector } from 'react-redux';
import { RootState } from '../../../redux/store';
import { toast } from "react-toastify";
import { PrivilegeContext } from '../../../components/priviledge/PriviledgeProvider';
import { LoginResetPasswordContainer } from './ResetPasswordContainer';
import { LoginResetPasswordForm } from './ResetPasswordForm';

const ResetPassword: FC = () => {

  const navigate = useNavigate();
  const { isAuthenticated, loading, error } = useSelector((state: RootState) => state.auth);


  const handleResetPassword = async (userToken: string, password: string, password_confirmation: string) => {
    const response = await (await (new LoginService()).resetForgotPassword(userToken, password, password_confirmation)).getResponseData();

    if (!response.success) {
      toast.error(response.message);
      return;
    }

    if (response.success) {
      try {
        navigate('/login', { replace: true });
        toast.success('Contrasenã actualizada con exito.');
      } catch (error) {
        toast.error('Error updating password. Please try again later.');
        return;
      }
    }
  };

  return (
    <LoginResetPasswordContainer>
      {error && <Alert color='warning' isLight icon='Warning'> {error} </Alert>}
      <LoginResetPasswordForm isLoading={loading} submit={handleResetPassword} errorsBool={error != null} />
    </LoginResetPasswordContainer>
  );
};

export default ResetPassword;