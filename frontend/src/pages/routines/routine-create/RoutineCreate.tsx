import { Fragment, useState } from "react";
import Card, {
  CardHeader,
  CardBody,
  CardTitle,
} from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import { adminMenu } from "../../../menu";
import { useNavigate } from "react-router-dom";
import { RoutineService } from "../../../services/routines/routineService";
import { toast } from "react-toastify";
import RoutineForm from "../RoutineForm";
import { CreateRoutineFieldsModel } from "../../../type/routine-type";
import Button from "../../../components/bootstrap/Button";
import { SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { SetMeal } from "../../../components/icon/material-icons";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";

const CreateRoutine = () => {
  const navigate = useNavigate();
  const { handleErrors } = useHandleErrors();
  const isAdmin = userIsAdmin() || userIsSuperAdmin();

  const [loading, setLoading] = useState<boolean>(false);

  const handleCreation = async (values: CreateRoutineFieldsModel) => {
    try {
      setLoading(true);
      let response = await (
        await new RoutineService().createRoutine(values)
      ).getResponseData();
      if (response.success) {
        toast.success(response.message);
        if (isAdmin) {
          navigate(adminMenu.routines.path, { replace: true });
        } else {
          navigate(adminMenu.userHasRoutines.path, { replace: true });
        }
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      toast.error("Error al crear la rutina");
    } finally {
      setLoading(false);
    }
  };

  return (
    <Fragment>
      <Page container="xxl">
        <Card stretch={true} className="m-auto">
          <CardHeader borderSize={1} className="d-flex justify-content-start">
            <Button
              color="primary"
              isLink
              icon="ArrowBack"
              onClick={() => navigate(-1)}
            />
            <SubheaderSeparator className="mx-3" />
            <SetMeal fontSize={"30px"} color="rgba(0, 0, 0, 0.3)" />
            <CardTitle className="fs-3 mb-0 ms-3">Crear Rutina</CardTitle>
          </CardHeader>
          <CardBody>
            <RoutineForm submit={handleCreation} isLoading={loading} />
          </CardBody>
        </Card>
      </Page>
    </Fragment>
  );
};

export default CreateRoutine;
