import { Fragment, useState } from "react";
import Card, { CardHeader, CardBody, CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import { adminMenu } from "../../../menu";
import { useNavigate } from "react-router-dom";
import { DietService } from "../../../services/diets/dietService";
import { toast } from "react-toastify";
import DietForm from "../DietForm";
import { CreateDietFieldsModel } from "../../../type/diet-type";
import Button from "../../../components/bootstrap/Button";
import { SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { SetMeal } from "../../../components/icon/material-icons";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";

const CreateDiet = () => {
    const isAdmin = userIsAdmin() || userIsSuperAdmin();
    const navigate = useNavigate();

    const [loading, setLoading] = useState<boolean>(false);

    const handleCreation = async (values: CreateDietFieldsModel) => {
        try {
            setLoading(true)
            let response = await (await (new DietService()).createDiet(values)).getResponseData();
            if (response.success) {
                toast.success(response.message);
                if(isAdmin) {
                    navigate(adminMenu.diets.path, { replace: true })
                } else {
                    navigate(adminMenu.userHasDiets.path, { replace: true })
                }
            }
        } catch (error: any) {
            toast.error('Error al crear la rutina');
        } finally {
            setLoading(false);
        }
    };

    return (
        <Fragment>
            <Page container='fluid'>
                <Card className="col-md-9 m-auto">
                    <CardHeader borderSize={1} className="d-flex justify-content-start">
                        <Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
                        <SubheaderSeparator className="mx-3" />
                        <SetMeal fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
                        <CardTitle className="fs-3 mb-0 ms-3">Crear Dieta</CardTitle>
                    </CardHeader>
                    <CardBody>
                        <DietForm submit={handleCreation} isLoading={loading} />
                    </CardBody>
                </Card>
            </Page>
        </Fragment>
    )
}

export default CreateDiet;