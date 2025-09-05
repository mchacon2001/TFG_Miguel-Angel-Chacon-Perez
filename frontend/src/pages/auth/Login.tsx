import { FC, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import Alert from '../../components/bootstrap/Alert';
import { LoginService } from '../../services/auth/loginService';
import { LoginContainer } from './LoginContainer';
import { LoginForm } from './LoginForm';
import { useDispatch, useSelector } from 'react-redux';
import { AppDispatch, RootState } from '../../redux/store';
import { login, User } from '../../redux/authSlice';
import { toast } from "react-toastify";
import { PrivilegeContext } from '../../components/priviledge/PriviledgeProvider';

const Login: FC = () => {

  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const dispatch = useDispatch<AppDispatch>();
  const { isAuthenticated, loading, error } = useSelector((state: RootState) => state.auth);

  if (isAuthenticated) {
    setTimeout(() => {
     if (userCan('list', 'routines')) {
      navigate('/user-routines', { replace: true })
      } else if (userCan('admin_routines', 'routines')) {
        navigate('/routines', { replace: true })
      } else if (userCan('list', 'diets')) {
        navigate('/diets', { replace: true })
      } else if (userCan('list', 'exercises')) {
        navigate('/exercises', { replace: true })
      } else if (userCan('list', 'educative_resources')) {
        navigate('/educative-resources', { replace: true })
      } else if (userCan('list', 'users')) {
        navigate('/users', { replace: true })
      } else if (userCan('list', 'roles')) {
        navigate('/roles', { replace: true })
      } else if (userCan('list', 'exercise_categories')) {
        navigate('/exercise-categories', { replace: true })
      } else if (userCan('list', 'routine_categories')) {
        navigate('/routine-categories', { replace: true })
      } else if (userCan('list', 'food')) {
        navigate('/food', { replace: true })
      } else {
        navigate('/', { replace: true });
      }
    }, 100);
  }

  const handleLogin = async (username: string, password: string) => {

    const response = await (await (new LoginService()).authUserCredentials(username, password)).getResponseData();

    if (!response.success) {
      toast.error(response.message);
      dispatch(
        login(
          {
            isAuthenticated: false,
            loading: false,
            error: response.message,
            user: null
          }
        )
      )
      return;
    }

    if (response.success) {
      try {
        const user: User = {
          id: response.data.user.id,
          token: response.data.token,
          name: response.data.user.name,
          profilePictureId: response.data.user.imgProfile,
          roles: response.data.user.roles,
        };

        dispatch(login(
          {
            isAuthenticated: true,
            loading: false,
            error: null,
            user: user
          }
        ))
      } catch (error) {
        toast.error('Error saving user to indexDb');
        return;
      }
    }

    if (response.payload) { navigate('/users', { replace: true }) };
  };

  return (

    <LoginContainer>
      {error && <Alert color='warning' isLight icon='Warning'> {error} </Alert>
      }
      <LoginForm isLoading={loading} submit={handleLogin} errorsBool={error != null} />
    </LoginContainer>
  );
};

export default Login;