import { Fragment, useCallback, useState } from "react";
import Button from "../../../components/bootstrap/Button";
import Card, { CardActions, CardHeader, CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft } from "../../../layout/SubHeader/SubHeader";
import { useNavigate, useParams } from "react-router-dom";
import { RoutineService } from "../../../services/routines/routineService";
import { RoutineApiResponse, EditRoutineFieldsModel } from "../../../type/routine-type";
import useFetch from "../../../hooks/useFetch";
import StatusSwitch from "../../../components/StatusSwitch";
import { toast } from "react-toastify";
import RoutineForm from "../RoutineForm";
import { adminMenu } from "../../../menu";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";

const EditRoutine = () => {

    const { id = '' } = useParams<{ id: string }>();
    const navigate = useNavigate();
    const { handleErrors } = useHandleErrors();
    const routineService = new RoutineService();
    const isAdmin = userIsAdmin() || userIsSuperAdmin();

    const [loading, setLoading] = useState<boolean>(false);
    const [changingStatus, setChangingStatus] = useState<boolean>(false);

    const [data] = useFetch(useCallback(async () => {
        const response = await routineService.getRoutineForEdit(id as string);
        return response.getResponseData() as RoutineApiResponse;
    }, [id]));

    const handleUpdate = async (values: EditRoutineFieldsModel) => {
        setLoading(true);
        try {
            let response = (await routineService.editRoutine({ ...values, routineId: id })).getResponseData();
            if (response.success) {
                toast.success(response.message);
                if (isAdmin) {
                    navigate(adminMenu.routines.path, { replace: true });
                } else {
                    navigate(adminMenu.userHasRoutines.path, { replace: true });
                }
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
            const routineData: EditRoutineFieldsModel = {
                routineId: data.id,
                active: data.active,
                name: data.name,
                description: data.description,
                routineCategoryId: data.routineCategoryId,
                routineExercises: data.routineExercises || [],
                toGainMuscle: data.toGainMuscle ?? false,
                toLoseWeight: data.toLoseWeight ?? false,
                toMaintainWeight: data.toMaintainWeight ?? false,
                toImprovePhysicalHealth: data.toImprovePhysicalHealth ?? false,
                toImproveMentalHealth: data.toImproveMentalHealth ?? false,
                fixShoulder: data.fixShoulder ?? false,
                fixKnees: data.fixKnees ?? false,
                fixBack: data.fixBack ?? false,
                rehab: data.rehab ?? false,
              }; 
                         
            return (
                <Fragment>
                    <CardHeader borderSize={1} className="d-flex justify-content-between">
                        <div className="d-flex">
                            <CardTitle className="fs-3 mb-0 ms-3">Editar Rutina</CardTitle>
                        </div>
                    </CardHeader>
                    <RoutineForm isLoading={loading} submit={handleUpdate} data={routineData} />
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
            <Page container="xxl">
                <Card stretch={true}>
                    <>{getContent()}</>
                </Card>
            </Page>
        </Fragment>
    )
}

export default EditRoutine;