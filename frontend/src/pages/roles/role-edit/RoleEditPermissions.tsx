import { FC, Fragment, useCallback } from "react";
import { useNavigate, useParams } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardHeader, CardLabel, CardTitle, } from "../../../components/bootstrap/Card";
import Spinner from "../../../components/bootstrap/Spinner";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft } from "../../../layout/SubHeader/SubHeader";
import useFetch from "../../../hooks/useFetch";
import { RoleService } from "../../../services/auth/roleService";
import { Role } from "../../../type/role-type";
import RoleForm from "../RoleForm";
import ErrorMessage from "../../../components/ErrorMessage";

const RoleEditPermissions: FC = () => {
  const navigate = useNavigate();

  const { id } = useParams<{ id: string }>();

  const [dataRole, loadingRole, errorRole] = useFetch(useCallback(async () => {
    const roleService = new RoleService();
    const response = await roleService.getRoleById(id as string);
    return response.getResponseData() as Role;
  }, [id]));

  const getContent = () => {
    if (loadingRole) return <Spinner />;

    if (errorRole) return <ErrorMessage error={errorRole} />;

    if (dataRole !== null) {
      const roleData = {
        name: dataRole.name !== null ? dataRole.name : "",
        description: dataRole.description !== null ? dataRole.description : "",
        permissions: dataRole.permissions !== null ? dataRole.permissions : [],
      };

      return <RoleForm isLoading={false} submit={() => { }} roleData={roleData} />;
    }
  };

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Button
            color="primary"
            isLink
            icon="ArrowBack"
            onClick={() => navigate("/roles", { replace: true })}
          />
        </SubHeaderLeft>
      </SubHeader>
      <Page container="fluid">
        <Card stretch={true}>
          <CardHeader borderSize={1}>
            <CardLabel icon="BorderColor" iconColor="primary">
              <CardTitle>Editar Rol</CardTitle>
            </CardLabel>
          </CardHeader>
          {getContent()}
        </Card>
      </Page>
    </Fragment>
  );
};

export default RoleEditPermissions;