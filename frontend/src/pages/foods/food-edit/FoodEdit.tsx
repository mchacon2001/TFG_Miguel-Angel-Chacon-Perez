import { FC, Fragment, useCallback, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import FoodForm from "../FoodForm";
import useFetch from "../../../hooks/useFetch";
import { toast } from 'react-toastify';
import useHandleErrors from "../../../hooks/useHandleErrors";
import { Inventory2 } from "../../../components/icon/material-icons";
import { FoodService } from "../../../services/foods/foodService";
import { EditFoodFieldsModel, Food } from "../../../type/food-type";
import Card, { CardHeader, CardTitle } from "../../../components/bootstrap/Card";
import Button from "../../../components/bootstrap/Button";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import SubHeader, { SubHeaderLeft } from "../../../layout/SubHeader/SubHeader";
import Page from "../../../layout/Page/Page";

const FoodEdit: FC = () => {

  const { id = '' } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { handleErrors } = useHandleErrors();

  const [loading, setLoading] = useState<boolean>(false);

  const [entity] = useFetch(useCallback(async () => {
    const response = await (new FoodService()).getFoodById(id as string);
    return response.getResponseData() as Food;
  }, [id]));

  const handleUpdate = async (values: EditFoodFieldsModel) => {
    setLoading(true);
    const editData = {
      foodId: id,
      name: values.name,
      description: values.description,
      calories: values.calories,
      proteins: values.proteins,
      carbs: values.carbs,
      fats: values.fats,
      
    }
    try {
      let response = await (await (new FoodService()).editFood(editData)).getResponseData();
      if (response.success) {
        toast.success(response.message);
        navigate(-1);
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      toast.error('Error al editar alimento');
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
            <CardTitle className="fs-3 mb-0 ms-3">Editar Alimento</CardTitle>
          </CardHeader>
          <FoodForm isLoading={loading} submit={handleUpdate} entityData={entityData} />
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

export default FoodEdit;