import { Fragment, useCallback, useContext } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { ExerciseService } from "../../../services/exercises/exerciseService";
import { Exercise } from "../../../type/exercise-type";
import useFetch from "../../../hooks/useFetch";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import Button from "../../../components/bootstrap/Button";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { exerciseMenu } from "../../../menu";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";

const ExerciseView = () => {

    const { id = "", tabURL = "" } = useParams<{ id: string, tabURL: string }>();
    const navigate = useNavigate();
    const { userCan } = useContext(PrivilegeContext);
    const { handleErrors } = useHandleErrors();
    const user = useSelector((state: RootState) => state.auth.user);

    const [dataRaw] = useFetch(useCallback(async () => {
        const exerciseService = new ExerciseService();
        const response = await exerciseService.getExerciseById(id as string);
        return response?.getResponseData?.() ?? response;
    }, [id]));

    const data = dataRaw?.data ?? dataRaw;

    const _handleDelete = async () => {
        try {
            const response = await (await (new ExerciseService()).deleteExercise(id)).getResponseData();
            if (response.success) {
                navigate(-1);
                setTimeout(() => {
                    toast.success("Ejercicio eliminado correctamente");
                }, 100);
            } else {
                handleErrors(response);
            }
        } catch (error: any) {
            handleErrors(error);
        }
    };

    if (!data || !data.name) return null;

    const isAdmin = userIsAdmin() || false;
    const isSuperAdmin = userIsSuperAdmin() || false;
    const currentUserId = user?.id;
    
    // Check if user can edit this exercise
    const canEdit = userCan('edit', 'exercises') && (
        isAdmin || 
        isSuperAdmin || 
        (data.user && data.user.id === currentUserId)
    );
    
    // Check if user can delete this exercise
    const canDelete = userCan('delete', 'exercises') && (
        isAdmin || 
        isSuperAdmin || 
        (data.user && data.user.id === currentUserId)
    );

    return (
        <Fragment>
            <SubHeader>
                <SubHeaderLeft>
                    <Button color='primary' isLink icon='ArrowBack' onClick={() => navigate(-1)} />
                    <SubheaderSeparator />
                    <CardTitle className="fs-4">{data?.name}</CardTitle>
                </SubHeaderLeft>
                <SubHeaderRight>
                    {canEdit && <Button color='primary' isLink icon='Edit' onClick={() => navigate(`${exerciseMenu.exercises.path}/${id}/edit`)} />}
                    {canEdit && canDelete && <SubheaderSeparator />}
                    {canDelete && (
                        <Button
                            color='primary' isLink icon='Delete'
                            onClick={() => {
                                handleConfirmationAlert({
                                    title: "Eliminar ejercicio",
                                    icon: "warning",
                                    onConfirm: _handleDelete
                                })
                            }}
                        />
                    )}
                </SubHeaderRight>
            </SubHeader>

            <Page container='fluid'>
                <div className="row justify-content-center">
                    <div className="col-md-10 col-lg-8">
                        <div className="card shadow-lg mb-4 border-0" style={{ background: "#f8fafc" }}>
                            <div className="row g-0 align-items-center">
                                <div className="col-md-5 text-center p-4">
                                    {data.imageUrl ? (
                                        <img
                                            src={data.imageUrl}
                                            alt={data.name}
                                            style={{ maxWidth: "100%", borderRadius: "16px", boxShadow: "0 2px 12px #0001" }}
                                        />
                                    ) : (
                                        <div
                                            style={{
                                                width: "100%",
                                                height: "220px",
                                                display: "flex",
                                                alignItems: "center",
                                                justifyContent: "center",
                                                background: "#e3e6ea",
                                                borderRadius: "16px"
                                            }}
                                        >
                                            <span style={{ fontSize: 80, color: "#bdbdbd" }}>🏋️‍♂️</span>
                                        </div>
                                    )}
                                    {data.videoUrl && (
                                        <div className="mt-3">
                                            <a
                                                href={data.videoUrl}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="btn btn-outline-primary btn-sm"
                                            >
                                                Ver video de demostración
                                            </a>
                                        </div>
                                    )}
                                </div>
                                <div className="col-md-7 p-4">
                                    <h2 className="fw-bold mb-2" style={{ color: "#1976d2" }}>{data.name}</h2>
                                    <div className="mb-3">
                                        {data.exerciseCategories && (
                                            <span className="badge bg-primary bg-opacity-75 me-2 fs-6">
                                                {data.exerciseCategories.name}
                                            </span>
                                        )}
                                        {data.difficulty && (
                                            <span className="badge bg-warning text-dark me-2 fs-6">
                                                Dificultad: {data.difficulty}
                                            </span>
                                        )}
                                        {data.muscleGroup && (
                                            <span className="badge bg-success bg-opacity-75 me-2 fs-6">
                                                Grupo muscular: {data.muscleGroup}
                                            </span>
                                        )}
                                        {data.equipment && (
                                            <span className="badge bg-info text-dark me-2 fs-6">
                                                Equipo: {data.equipment}
                                            </span>
                                        )}
                                    </div>
                                    <div className="mb-3">
                                        {data.user ? (
                                            <span className="text-muted">
                                                <strong>Creado por:</strong>{" "}
                                                {data.user.name === "SuperAdmin" || data.user.name === "Admin"
                                                    ? "BrainyGym"
                                                    : data.user.name}
                                            </span>
                                        ) : (
                                            <span className="text-muted"><strong>Creado por:</strong> N/A</span>
                                        )}
                                    </div>
                                </div>
                            </div>
                            {data.description && data.description.trim() !== "" && (
                                <div className="row">
                                    <div className="col-12 px-5 pb-4">
                                        <h5 className="fw-bold mb-2 mt-4">Descripción</h5>
                                        <div className="fs-6" style={{ color: "#444", whiteSpace: 'pre-wrap' }}>
                                            {data.description}
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </Page>
        </Fragment>
    )
}

export default ExerciseView;