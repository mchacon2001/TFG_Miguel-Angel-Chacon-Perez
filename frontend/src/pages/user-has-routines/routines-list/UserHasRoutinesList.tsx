import { useContext, useCallback, Fragment, useState } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import useFetch from "../../../hooks/useFetch";
import { RoutineService } from "../../../services/routines/routineService";
import { RoutinesApiResponse } from "../../../type/routine-type";
import moment from "moment";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import { CustomTable } from "../../../components/table/CustomTable";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubheaderSeparator, SubHeaderRight } from "../../../layout/SubHeader/SubHeader";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";
import ErrorMessage from "../../../components/ErrorMessage";
import StatusDropdown from "../../../components/StatusDropdown";
import RoutinesFilters from "./routines-options/UserHasRoutinesFilters";
import { routinesMenu } from "../../../menu";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import { UserHasRoutineService } from "../../../services/user-has-routine/userHasRoutineService";
import { RoutineRegisterService } from "../../../services/routine-register/routineRegisterService";
import RoutineDayPlayModal from "../components/RoutineDayPlayModal";
import ActiveRoutineModal from "../components/ActiveRoutineModal";

const UserHasRoutinesList = () => {
    const { userCan } = useContext(PrivilegeContext);
    const navigate = useNavigate();
    const userHasRoutineService = new UserHasRoutineService();
    const routineRegisterService = new RoutineRegisterService();
    const isAdmin = userIsAdmin() || false;
    const isSuperAdmin = userIsSuperAdmin() || false;
    const user = useSelector((state: RootState) => state.auth.user);
    
    const [showPlayModal, setShowPlayModal] = useState(false);
    const [showActiveRoutineModal, setShowActiveRoutineModal] = useState(false);
    const [routineDays, setRoutineDays] = useState<any[]>([]);
    const [selectedRoutineId, setSelectedRoutineId] = useState<string | null>(null);
    const [activeRoutine, setActiveRoutine] = useState<any>(null);
    const [pendingPlayData, setPendingPlayData] = useState<any>(null);

    const checkActiveRoutine = async (routineId: string) => {
        try {
            const response = await routineRegisterService.getActiveRoutineByUser(user?.id, routineId);
            const data = response.getResponseData();
            
            if (data.success && data.data) {
                return data.data;
            }
            return null;
        } catch (error) {
            console.error("Error al verificar rutina activa:", error);
            return null;
        }
    };

    const handleOpenPlayModal = async (item: any) => {
        const routineId = item.routine.id ?? item.id;
        
        const activeRoutineData = await checkActiveRoutine(routineId);
        
        if (activeRoutineData) {
            setActiveRoutine(activeRoutineData);
            setPendingPlayData(item);
            setShowActiveRoutineModal(true);
            return;
        }

        showDaySelectionModal(item);
    };

    const showDaySelectionModal = (item: any) => {
        const routineHasExercise = item.routine?.routineHasExercise || [];
        const daysMap: { [key: number]: any[] } = {};
        
        routineHasExercise.forEach((ex: any) => {
            if (!daysMap[ex.day]) daysMap[ex.day] = [];
            daysMap[ex.day].push(ex);
        });
        
        // Crea un array de días [{ number: 1, exercises: [...] }, ...]
        const days = Object.keys(daysMap).map((dayNum: string) => ({
            number: Number(dayNum),
            exercises: daysMap[parseInt(dayNum, 10)]
        }));
        
        setSelectedRoutineId(item.routine.id ?? item.id);
        setRoutineDays(days);
        setShowPlayModal(true);
    };

    const handleContinueActiveRoutine = () => {
        setShowActiveRoutineModal(false);
        
        if (activeRoutine) {
            navigate(`/routines/${activeRoutine.id}/${activeRoutine.day}/routine-register/`);
        }     
        setActiveRoutine(null);
        setPendingPlayData(null);
        setSelectedRoutineId(null);
    };

    const handleFinishAndStartNew = async () => {
        try {
            setShowActiveRoutineModal(false);
            
            if (activeRoutine) {
                await routineRegisterService.finishRoutineRegister(activeRoutine.id);
                toast.success("Rutina anterior finalizada");
            }
            
            if (pendingPlayData) {
                showDaySelectionModal(pendingPlayData);
            }
            
        } catch (error) {
            toast.error("Error al finalizar la rutina anterior");
        } finally {
            setActiveRoutine(null);
            setPendingPlayData(null);
        }
    };

    const handlePlayDay = async (day: any) => {
        try {
            const response = await routineRegisterService.createRoutineRegister({
                routineId: selectedRoutineId,
                day: day.number,
            });
            
            const data = response.getResponseData();
            const routineRegisterId = data?.data?.routineRegister;
            
            setShowPlayModal(false);
            
            if (routineRegisterId) {
                navigate(`/routines/${routineRegisterId}/${day.number}/routine-register`);
            } else {
                toast.error("No se pudo iniciar la rutina (ID no recibido).");
            }
        } catch (error) {
            toast.error("No se pudo iniciar la rutina.");
        }
    };

    const [changingStatus, setChangingStatus] = useState<string[]>([]);
    const [isOpen, setIsOpen] = useState<boolean>(false);
    const [selectedRoutine, setSelectedRoutine] = useState<any>(null);

    const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();
    const [data, loading, error, refetch] = useFetch(useCallback(async () => {
        if (user?.id) {
            const filtersWithUser = {
                ...filters,
                filter_filters: { 
                    ...filters.filter_filters, 
                    user: user.id 
                }
            };

            const response = await userHasRoutineService.listUserHasRoutines(filtersWithUser);
            return response.getResponseData() as RoutinesApiResponse;
        }
        return null;
    }, [filters, user?.id]));

    /**
     * Delete a routine with the given ID.
     */
    const deleteRoutine = async (id: string) => {
        let response = (await userHasRoutineService.deleteUserHasRoutine(id)).getResponseData();
        if (response.success) {
            toast.success("Rutina eliminada correctamente");
            refetch();
        }
    };

    return (
        <Fragment>
            <SubHeader>
                <SubHeaderLeft>
                    <Fragment>
                        <CardTitle>Listado de Rutinas asignadas</CardTitle>
                        {userCan("create", "routines") &&
                            <>
                                <SubheaderSeparator />
                                <Button color="light" icon="Add" isLight onClick={() => { navigate("create") }}>
                                    Crear Rutina
                                </Button>
                            </>
                        }
                        <SubheaderSeparator />
                    </Fragment>
                </SubHeaderLeft>
                <SubHeaderRight>
                    <RoutinesFilters filters={filters} updateFilters={updateFilters} resetFilters={resetFilters} />
                </SubHeaderRight>
            </SubHeader>

            <Page container="fluid">
                <Card stretch={false}>
                    {error && <ErrorMessage />}

                    {(data && data.routines)
                        ? (
                            <CustomTable
                                data={data?.routines ? data.routines : null}
                                pagination={true}
                                paginationData={{
                                    pageSize: filters.limit,
                                    currentPage: filters.page,
                                    pageCount: (data as RoutinesApiResponse) ? data.lastPage : 1,
                                    totalCount: data.totalRegisters,
                                    handlePagination: updatePage,
                                    handlePerPage: updatePageSize,
                                }}
                                defaultLimit={filters.limit || 50}
                                defaultOrder={filters.filter_order || undefined}
                                className={"table table-hover"}
                                columns={[
                                    {
                                        name: "Nombre",
                                        keyValue: "name",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        className: "text-center",
                                        render: (element: any) => {
                                            return (
                                                <div className="text-center cursor-pointer name-link fw-bold" onClick={() => navigate(`${routinesMenu.routines.path}/${element.routine.id}`)}>
                                                    {element.routine.name}
                                                </div>
                                            );
                                        },
                                    },
                                    {
                                        name: "Categoría",
                                        keyValue: "routine_category",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        className: "text-center",
                                        render: (element: any) => {
                                            return (
                                                <div className="text-center">
                                                    {element.routine.routineCategory.name}
                                                </div>
                                            );
                                        },
                                    },
                                    {
                                        name: "Duración",
                                        keyValue: "quantity",
                                        className: "text-center",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        render: (element: any) => {
                                            return (
                                                <div className="text-center">
                                                    {element.routine.quantity}
                                                </div>
                                            );
                                        },
                                    },
                                    {
                                        name: "Creador",
                                        keyValue: "user",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        className: "text-center",
                                        render: (element: any) => {
                                            const creator = element.routine.user;
                                            const isBrainyGym = creator?.userRoles?.some(
                                                (role: any) => role.role?.id === 1 || role.role?.id === 2
                                            );
                                            return (
                                                <div className="d-flex justify-content-center text-center">
                                                    {creator
                                                        ? (isBrainyGym ? "BrainyGym" : creator.name)
                                                        : 'N/A'}
                                                </div>
                                            );
                                        },
                                    },
                                    {
                                        name: "Fecha de creación",
                                        keyValue: "created_at",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        className: `text-center`,
                                        render: (element: any) => {
                                            return (
                                                <div className={"text-center"}>
                                                    <span className={"text-muted"}>
                                                        {moment(element.routine.createdAt.date).format(
                                                            "DD-MM-YYYY"
                                                        ) ?? "N/A"}
                                                    </span>
                                                </div>
                                            );
                                        },
                                    },
                                    { name: "Acciones", className: "min-w-100px text-end", isActionCell: true },
                                ]}
                                actions={[
                                    {
                                        title: "Play",
                                        buttonType: "icon",
                                        iconColor: "text-success",
                                        iconPath: "/media/icons/duotune/arrows/arr027.svg",
                                        additionalClasses: "me-2",
                                        description: "Iniciar rutina",
                                        callback: (item: any) => { handleOpenPlayModal(item); },
                                    },
                                    {
                                        title: "Editar",
                                        buttonType: "icon",
                                        iconColor: "text-info",
                                        iconPath: "/media/icons/duotune/general/gen055.svg",
                                        additionalClasses: "text-primary",
                                        description: "Editar rutina",
                                        hide: (item: any) => {
                                            if (!userCan('edit', 'routines')) return true;
                                            if (isAdmin || isSuperAdmin) return false;
                                            const currentUserId = user?.id;
                                            return !(item.routine.user && item.routine.user.id === currentUserId);
                                        },
                                        callback: (item: any) => { navigate(`${item.routine.id}/edit`) },
                                    },
                                    {
                                        title: "Eliminar",
                                        buttonType: "icon",
                                        iconColor: "text-danger",
                                        iconPath: "/media/icons/duotune/general/gen027.svg",
                                        additionalClasses: "text-danger",
                                        description: "Eliminar rutina",
                                        hide: (item: any) => {
                                            if (!userCan('delete', 'routines')) return true;
                                            if (isAdmin || isSuperAdmin) return false;
                                            const currentUserId = user?.id;
                                            return !(item.routine.user && item.routine.user.id === currentUserId);
                                        },
                                        callback: (item: any) => {
                                            handleConfirmationAlert({
                                                title: "Eliminar rutina",
                                                text: "Esta acción eliminará esta rutina de forma irreversible. ¿Estás seguro de que quieres eliminar esta rutina?",
                                                icon: "warning",
                                                onConfirm: () => { deleteRoutine(item.id) },
                                            });
                                        },
                                    }
                                ]}
                            />
                        )
                        : !error && <Loader />
                    }
                </Card>
            </Page>

            {/* Modal para seleccionar día */}
            <RoutineDayPlayModal
                isOpen={showPlayModal}
                setIsOpen={setShowPlayModal}
                routineDays={routineDays}
                handlePlayDay={handlePlayDay}
            />

            {/* Modal para rutina activa */}
            <ActiveRoutineModal
                isOpen={showActiveRoutineModal}
                setIsOpen={setShowActiveRoutineModal}
                activeRoutine={activeRoutine}
                onContinue={handleContinueActiveRoutine}
                onFinishAndStart={handleFinishAndStartNew}
            />
        </Fragment>
    );
};

export default UserHasRoutinesList;