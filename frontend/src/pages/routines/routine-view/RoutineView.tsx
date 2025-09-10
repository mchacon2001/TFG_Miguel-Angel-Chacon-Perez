import { Fragment, useCallback, useContext, useEffect } from "react";
import { useNavigate, useParams, Link } from "react-router-dom";
import useFetch from "../../../hooks/useFetch";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import Button from "../../../components/bootstrap/Button";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { routinesMenu } from "../../../menu";
import { Exercise, RoutineHasExercise } from "../../../type/exercise-type";
import { RoutineService } from "../../../services/routines/routineService";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import useFilters from "../../../hooks/useFilters";
import moment from "moment";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";

interface IChartsFilters {
    routine_id: string;
    date: {
        from: string;
        to: string;
        mode: string;
    }
    show_by: string;
}

const initialFilters: IChartsFilters = {
    routine_id: "",
    date: {
        from: moment().subtract(1, 'months').format('YYYY-MM-DD'),
        to: moment().format('YYYY-MM-DD'),
        mode: "days",
    },
    show_by: 'day'
}

const RoutineView = () => {

    const { id = "", dayNumber = "" } = useParams<{ id: string, dayNumber: string }>();
    const navigate = useNavigate();
    const { userCan } = useContext(PrivilegeContext);
    const { handleErrors } = useHandleErrors();
    const service = new RoutineService();
    const user = useSelector((state: RootState) => state.auth.user);
    const isAdmin = userIsAdmin() || userIsSuperAdmin();
    

    const { filters, updateFilters, resetFilters } = useFilters(initialFilters, [], 1, 9999999);

    const [data, loading] = useFetch(useCallback(async () => {
        const response = await service.getRoutineById(id as string);
        return response.getResponseData() as Exercise;
    }, [id]));


    useEffect(() => {
        if (id) updateFilters({ routine_id: id });
    }, [id]);

    if (loading) return <Loader />;
    if (!data) return null;

    const exercisesForDay = data?.routineHasExercise?.filter((exercise: RoutineHasExercise) => exercise.day.toString() === dayNumber);

    return (
        <Fragment>
            <SubHeader>
                <SubHeaderLeft>
                    <Button color='primary' isLink icon='ArrowBack' onClick={() => navigate(-1)} />
                    <SubheaderSeparator />
                    <CardTitle className="me-4 fs-4">{data?.name}</CardTitle>
                </SubHeaderLeft>
                <SubHeaderRight>
                    {(isAdmin || (user && data?.user.id === user.id)) && userCan('edit', 'routines') && (
                        <Button color='primary' isLink icon='Edit' onClick={() => navigate(`${routinesMenu.routines.path}/${id}/edit`)} />
                    )}
                </SubHeaderRight>
            </SubHeader>

            <Page container='fluid'>
                <div className="row">
                    <div className="col-md-12">
                        {exercisesForDay?.length > 0 ? (
                            exercisesForDay.map((exerciseData: RoutineHasExercise, exerciseIndex: number) => (
                                <div key={exerciseIndex} className="mb-4">
                                    <div className="card mb-3">
                                        <div className="card-body">
                                            <h5 className="card-title">
                                              <Link to={`/exercises/${exerciseData.exercise.id}/view`} className="name-link">
                                                {exerciseData.exercise.name}
                                              </Link>
                                            </h5>
                                            <p className="card-text">
                                                <strong>Series:</strong> {exerciseData.sets} <br />
                                                <strong>Repeticiones:</strong> {exerciseData.reps} <br />
                                                <strong>Descanso</strong> {exerciseData.restTime} segundos <br />
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <p>No hay ejercicios para este día.</p>
                        )}
                    </div>
                </div>
            </Page>
        </Fragment>
    );
};

export default RoutineView;
