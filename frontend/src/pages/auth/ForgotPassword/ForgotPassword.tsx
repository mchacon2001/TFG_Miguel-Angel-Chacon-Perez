import { FC, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import Alert from '../../../components/bootstrap/Alert';
import { LoginService } from '../../../services/auth/loginService';
import { useSelector } from 'react-redux';
import { RootState } from '../../../redux/store';
import { toast } from "react-toastify";
import { PrivilegeContext } from '../../../components/priviledge/PriviledgeProvider';
import { LoginForgotPasswordForm } from './ForgotPasswordForm';
import { LoginForgotPasswordContainer } from './ForgotPasswordContainer';

const LoginForgotPassword: FC = () => {

  const navigate = useNavigate();
  const { isAuthenticated, loading, error } = useSelector((state: RootState) => state.auth);

  const handleEmailForgotPassword = async (username: string) => {
    const response = await (await (new LoginService()).sendEmailForgotPassword(username)).getResponseData();

    if (!response.success) {
      toast.error(response.message);
      return;
    }

    if (response.success) {
      try {
        navigate('/login', { replace: true });
        toast.success('Email enviado. Por favor revise su correo.');
      } catch (error) {
        toast.error('Error sending email. Please try again later.');
        return;
      }
    }
  };

  return (
    <LoginForgotPasswordContainer>
      {error && <Alert color='warning' isLight icon='Warning'> {error} </Alert>}
      <LoginForgotPasswordForm isLoading={loading} submit={handleEmailForgotPassword} errorsBool={error != null} />
    </LoginForgotPasswordContainer>
  );
};

export default LoginForgotPassword;