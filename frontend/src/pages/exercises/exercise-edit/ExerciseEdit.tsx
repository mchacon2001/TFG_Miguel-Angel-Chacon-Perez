import { FC, Fragment, useCallback, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import ExerciseForm from "../ExerciseForm";
import useFetch from "../../../hooks/useFetch";
import { toast } from 'react-toastify';
import useHandleErrors from "../../../hooks/useHandleErrors";
import { Inventory2 } from "../../../components/icon/material-icons";
import { ExerciseService } from "../../../services/exercises/exerciseService";
import { EditExerciseFieldsModel, Exercise } from "../../../type/exercise-type";
import Card, { CardHeader, CardTitle } from "../../../components/bootstrap/Card";
import Button from "../../../components/bootstrap/Button";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import SubHeader, { SubHeaderLeft } from "../../../layout/SubHeader/SubHeader";
import Page from "../../../layout/Page/Page";

const ExerciseEdit: FC = () => {

  const { id = '' } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { handleErrors } = useHandleErrors();

  const [loading, setLoading] = useState<boolean>(false);

  const [entity] = useFetch(useCallback(async () => {
    const response = await (new ExerciseService()).getExerciseById(id as string);
    return response.getResponseData() as Exercise;
  }, [id]));

  const handleUpdate = async (values: EditExerciseFieldsModel) => {
    setLoading(true);
    const editData = {
      exerciseId: id,
      exerciseCategoryId: values.exerciseCategoryId,
      name: values.name,
      description: values.description ?? '',
    }
    try {
      let response = await (await (new ExerciseService()).editExercise(editData)).getResponseData();
      if (response.success) {
        toast.success(response.message);
        navigate(-1);
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      toast.error('Error al editar ejercicio');
    } finally {
      setLoading(false);
    }
  };

  const getContent = () => {
    if (loading) return <Loader />;

    if (entity !== null) {
      const entityData = {
        ...entity,
      };

      return (
        <Fragment>
          <CardHeader borderSize={1} className="d-flex justify-content-start">
            <Inventory2 fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
            <CardTitle className="fs-3 mb-0 ms-3">Editar Ejercicio</CardTitle>
          </CardHeader>
          <ExerciseForm isLoading={loading} submit={handleUpdate} entityData={entityData} />
        </Fragment>
      );
    }
  };

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
        </SubHeaderLeft>
      </SubHeader>
      <Page container="fluid">
        <Card stretch={true} className="col-md-9 m-auto">
          <>{getContent()}</>
        </Card>
      </Page>
    </Fragment>
  );
};

export default ExerciseEdit;