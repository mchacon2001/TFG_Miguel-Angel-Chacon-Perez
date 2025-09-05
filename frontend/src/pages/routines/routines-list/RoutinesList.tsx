import { useContext, useCallback, Fragment, useState, useEffect } from "react";
import { replace, useNavigate } from "react-router-dom";
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
import RoutinesFilters from "./routines-options/RoutinesFilters";
import { routinesMenu } from "../../../menu";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import AssignUserRoutineModal from "../components/AssignUserRoutineModal";
import { UserHasRoutineService } from "../../../services/user-has-routine/userHasRoutineService";
import { is } from "date-fns/locale";

const RoutinesList = () => {

    const { userCan } = useContext(PrivilegeContext);
    const navigate = useNavigate();
    const routineService = new RoutineService();
    const user = useSelector((state: RootState) => state.auth.user);

    const isAdmin = userIsAdmin();
    const isSuperAdmin = userIsSuperAdmin();
    const [assignedUsers, setAssignedUsers] = useState<any[]>([])

    const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();

    const [changingStatus, setChangingStatus] = useState<string[]>([]);
    const [isOpen, setIsOpen] = useState<boolean>(false);
    const [selectedRoutine, setSelectedRoutine] = useState<any>(null);

    const [data, loading, error, refetch] = useFetch(useCallback(async () => {
        const response = await routineService.getRoutines(filters);
        return response.getResponseData() as RoutinesApiResponse;
    }, [filters]));

    /**
     * Delete a routine with the given ID.
     *
     * @param {string} id - The ID of the routine to be deleted.
     */
    const deleteRoutine = async (id: string) => {
        let response = (await routineService.deleteRoutine(id)).getResponseData();
        if (response.success) {
            toast.success("Rutina eliminada correctamente");
            refetch();
        }
    };

    const openAssignModal = async (routine: any) => {
    const response = await new UserHasRoutineService().listUserHasRoutines();
    setAssignedUsers(response.response?.data?.data || []);
    setSelectedRoutine(routine);
    setIsOpen(true);
    };

    return (
        <Fragment>
            <SubHeader>
                <SubHeaderLeft>
                    <Fragment>
                        <CardTitle>Listado de Rutinas</CardTitle>
                        {userCan("create", "routines") &&
                            <>
                                <SubheaderSeparator />
                                <Button color="light" icon="Add" isLight onClick={() => { navigate("create") }}>
                                    Añadir Rutina
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
                                                <div className="text-center cursor-pointer name-link fw-bold" onClick={() => navigate(`${routinesMenu.routines.path}/${element.id}`)}>
                                                    {element.name}
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
                                                    {element.routineCategory.name}
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
                                                    {element.quantity}
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
                                            return (
                                                <div className="d-flex justify-content-center text-center">
                                                    {element.user
                                                        ? (element.user.userRoles?.some((role: any) => role.role.id === 1 || role.role.id === 2)
                                                            ? "BrainyGym"
                                                            : element.user.name)
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
                                                        {moment(element.createdAt.date).format(
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
                                        title: "Asignar usuarios",
                                        buttonType: "icon",
                                        iconColor: "text-success",
                                        iconPath: "/media/icons/duotune/general/gen041.svg",
                                        additionalClasses: "text-success",
                                        description: "Asignar usuarios a la rutina",
                                        hide: (item: any) => false,
                                        callback: (item: any) => {
                                           openAssignModal(item);

                                        },
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
                                        return !(item.user && item.user.id === currentUserId);
                                    },
                                        callback: (item: any) => { navigate(`${item.id}/edit`) },
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
                                        return !(item.user && item.user.id === currentUserId);
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

                        <AssignUserRoutineModal
                            isOpen={isOpen}
                            setIsOpen={setIsOpen}
                            routineId={selectedRoutine?.id || ""}
                            userRoutines={selectedRoutine?.userRoutines || []}
                        />
                    </Fragment>
                )
            }

export default RoutinesList;