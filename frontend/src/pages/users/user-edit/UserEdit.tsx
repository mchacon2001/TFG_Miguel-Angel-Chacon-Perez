import { FC, Fragment, useCallback, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardActions, CardHeader, CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight } from "../../../layout/SubHeader/SubHeader";
import UserForm from "../UserForm";
import { UserService } from "../../../services/users/userService";
import { EditUserFieldsModel, User } from "../../../type/user-type";
import useFetch from "../../../hooks/useFetch";
import UserEditPermissions from "./UserEditPermissions";
import { RolePermission } from "../../../type/role-type";
import StatusSwitch from "../../../components/StatusSwitch";
import { toast } from 'react-toastify';
import useHandleErrors from "../../../hooks/useHandleErrors";
import { isString } from "formik";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { Edit } from "../../../components/icon/material-icons";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { useSelector, useDispatch } from "react-redux";
import { RootState } from "../../../redux/store";
import { logout } from "../../../redux/authSlice";

const UserEdit: FC = () => {

  const navigate = useNavigate();
  const dispatch = useDispatch();
  const { handleErrors } = useHandleErrors();
  const { id = '' } = useParams<{ id: string }>();

  const [editPermissions, setEditPermissions] = useState<boolean>(false);
  const [userPermissions, setUserPermissions] = useState<RolePermission[] | null>(null);
  const [loading, setLoading] = useState<boolean>(false);

  const [data] = useFetch(useCallback(async () => {
    const userService = new UserService();
    const response = await userService.getUserById(id as string);
    setUserPermissions(response.getResponseData().data.userPermissions);
    return response.getResponseData() as User;
  }, [id, editPermissions]));

  const user = useSelector((state: RootState) => state.auth.user);

  const handleUpdate = async (values: EditUserFieldsModel) => {
    setLoading(true);

    (data?.userRoles[0].role?.id === values.roleId) && (values.roleId = null);

    // Guardar datos originales para comparar cambios
    const originalEmail = data?.email;
    const originalName = data?.name;

    try {
      if (isString(values.id) && values.password && values.re_password) {
        const response = ((await (new UserService()).changeUserPassword(values.id, values.password, values.re_password)).getResponseData())
        if (!response.success) {
          toast.error(response.message);
          return;
        }
      }
    } catch (error: any) {
      toast.error('Error al actualizar constraseña');
    }

    try {
      let response = await (await (new UserService()).editUser({ ...values, userId: values.id })).getResponseData();
      if (response.success) {
        toast.success(response.message);
        
        // Si el usuario editado es el mismo que el autenticado
        if (user?.id === values.id) {
          // Verificar si cambió información crítica
          const criticalDataChanged = 
            values.email !== originalEmail || 
            values.name !== originalName ||
            (values.password && values.password.length > 0); // Si cambió contraseña
            
          if (criticalDataChanged) {
            toast.info("Has actualizado información importante. Por seguridad, serás desconectado automáticamente. Vuelve a iniciar sesión con tus nuevos datos.", {
              autoClose: 5000
            });
            
            // Delay para que el usuario lea el mensaje
            setTimeout(() => {
              dispatch(logout());
              navigate('/auth/login', { replace: true });
            }, 5000);
          } else {
            navigate(`/users/${user?.id}/profile`, { replace: true });
          }
        } else {
          navigate('/users', { replace: true });
        }
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      toast.error('Error al editar Usuario');
    } finally {
      setLoading(false);
    }
  };


  const getContent = () => {
    if (loading) return <Loader />;

    if (data !== null) {
      const userData = {
        ...data,
        roleId: data.userRoles[0]?.role?.id,
        password: "",
        re_password: "",
      };

      return (
        <Fragment>
          <CardHeader borderSize={1} className="d-flex justify-content-between">
            <div className="d-flex">
              <Edit fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
              <CardTitle className="fs-3 mb-0 ms-3">Editar Usuario</CardTitle>
            </div>
          </CardHeader>
          <UserForm isLoading={loading} submit={handleUpdate} userData={userData} />
        </Fragment>
      );
    }
  };

  const isAdminOrSuperAdmin = userIsAdmin() || userIsSuperAdmin();

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
        </SubHeaderLeft>
        <SubHeaderRight>
          {/* Solo mostrar el botón de editar permisos si es admin o super admin */}
          {isAdminOrSuperAdmin && (
            <Button color="brand-two" icon="Security" onClick={() => { setEditPermissions(true) }}>
              Editar Permisos
            </Button>
          )}
        </SubHeaderRight>
      </SubHeader>
      <Page container="xxl">
        <Card className="col-md-8 m-auto">
          <>{getContent()}</>
        </Card>
      </Page>

      {editPermissions && data &&
        <UserEditPermissions
          userId={id}
          isOpen={editPermissions} setIsOpen={setEditPermissions}
          userPermissions={userPermissions} setUserPermissions={setUserPermissions}
        />
      }
    </Fragment>
  );
};

export default UserEdit;