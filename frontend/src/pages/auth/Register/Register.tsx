import { FC, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import Alert from '../../../components/bootstrap/Alert';
import { LoginService } from '../../../services/auth/loginService';
import { useSelector } from 'react-redux';
import { RootState } from '../../../redux/store';
import { toast } from "react-toastify";
import { PrivilegeContext } from '../../../components/priviledge/PriviledgeProvider';
import { RegisterForm } from './RegisterForm';
import { RegisterContainer } from './RegisterContainer';


const Register: FC = () => {

  const navigate = useNavigate();
  const { isAuthenticated, loading, error } = useSelector((state: RootState) => state.auth);

const handleRegister = async (name: string, email: string, password: string, sex: string, targetWeight: number, birthdate: string, height: number, weight: number, toGainMuscle: boolean, toLoseWeight: boolean, toMaintainWeight: boolean, toImprovePhysicalHealth: boolean, toImproveMentalHealth: boolean, fixShoulder: boolean, fixKnees: boolean, fixBack: boolean, rehab: boolean) => {

    try {
      let response = await (await (new LoginService()).registerUser(name,email, password, sex, targetWeight, birthdate, height, weight,toGainMuscle, toLoseWeight, toMaintainWeight, toImprovePhysicalHealth, toImproveMentalHealth, fixShoulder, fixKnees, fixBack, rehab)).getResponseData();
      if (response.success) {
        toast.success(response.message);
        navigate('/login', { replace: true })
      } else {
        toast.error(response.message);
      }
    } catch (error: any) {
      toast.error('Error al crear el usuario');
    } finally {
    }
  };
  

  return (
    <RegisterContainer>
      {error && <Alert color='warning' isLight icon='Warning'> {error} </Alert>}
      <RegisterForm isLoading={loading} submit={handleRegister} errorsBool={error != null} />
    </RegisterContainer>
    
  );
};

export default Register;