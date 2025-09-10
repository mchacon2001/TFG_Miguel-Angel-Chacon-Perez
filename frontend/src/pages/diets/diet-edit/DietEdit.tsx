import { Fragment, useCallback, useState } from "react";
import Button from "../../../components/bootstrap/Button";
import Card, { CardHeader, CardBody, CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { useNavigate, useParams } from "react-router-dom";
import { DietService } from "../../../services/diets/dietService";
import { DietApiResponse, EditDietFieldsModel } from "../../../type/diet-type";
import useFetch from "../../../hooks/useFetch";
import StatusSwitch from "../../../components/StatusSwitch";
import { toast } from "react-toastify";
import DietForm from "../DietForm";
import { adminMenu } from "../../../menu";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { SetMeal } from "../../../components/icon/material-icons";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";

const EditDiet = () => {

    const { id = '' } = useParams<{ id: string }>();
    const navigate = useNavigate();
    const { handleErrors } = useHandleErrors();
    const dietService = new DietService();
    const isAdmin = userIsAdmin() || userIsSuperAdmin();

    const [loading, setLoading] = useState<boolean>(false);

    const [data] = useFetch(useCallback(async () => {
        const response = await dietService.getDietForEdit(id as string);
        return response.getResponseData() as DietApiResponse;
    }, [id]));

    const handleUpdate = async (values: EditDietFieldsModel) => {
        setLoading(true);
        try {
            let response = (await dietService.editDiet({ ...values, dietId: id })).getResponseData();
            if (response.success) {
                toast.success(response.message);
                if(isAdmin){
                    navigate(adminMenu.diets.path, { replace: true });
                } 
                else
                {
                 navigate(adminMenu.userHasDiets.path, { replace: true });
                }
            } else {
                handleErrors(response);
            }
        } catch (error: any) {
            toast.error('Error al editar la rutina');
        } finally {
            setLoading(false);
        }
    };


    const getContent = () => {
        if (loading) return <Loader />;

        if (data !== null) {
            const dietData: EditDietFieldsModel = {
                dietId: data.id,
                name: data.name,
                description: data.description,
                goal: data.goal,
                dietFood: data.dietFood || [],
                // Add flag values from the retrieved data
                toGainMuscle: data.toGainMuscle || false,
                toLoseWeight: data.toLoseWeight || false,
                toMaintainWeight: data.toMaintainWeight || false,
            };

            return (
                <Fragment>
                    <CardHeader borderSize={1} className="d-flex justify-content-start">
                        <Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
                        <SubheaderSeparator className="mx-3" />
                        <SetMeal fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
                        <CardTitle className="fs-3 mb-0 ms-3">Editar Dieta</CardTitle>
                    </CardHeader>
                    <CardBody>
                        <DietForm isLoading={loading} submit={handleUpdate} data={dietData} />
                    </CardBody>
                </Fragment>
            );
        }
    };
    

    return (
        <Fragment>
            <Page container="fluid">
                <Card className="col-md-9 m-auto">
                    <>{getContent()}</>
                </Card>
            </Page>
        </Fragment>
    )
}

export default EditDiet;