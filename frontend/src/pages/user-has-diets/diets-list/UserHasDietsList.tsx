import { useContext, useCallback, Fragment, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import useFetch from "../../../hooks/useFetch";
import { DietsApiResponse } from "../../../type/diet-type";
import moment from "moment";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import { CustomTable } from "../../../components/table/CustomTable";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubheaderSeparator, SubHeaderRight } from "../../../layout/SubHeader/SubHeader";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";
import ErrorMessage from "../../../components/ErrorMessage";
import { dietMenu } from "../../../menu";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import { UserHasDietService } from "../../../services/user-has-diet/userHasDietService";
import DietsFilters from "../../diets/diets-list/diets-options/DietsFilters";

const UserHasDietsList = () => {
    const { userCan } = useContext(PrivilegeContext);
    const navigate = useNavigate();
    const userHasDietService = new UserHasDietService();
    const isAdmin = userIsAdmin() || false;
    const isSuperAdmin = userIsSuperAdmin() || false;
    const user = useSelector((state: RootState) => state.auth.user);

    const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();
    const [data, loading, error, refetch] = useFetch(useCallback(async () => {
        if (user?.id) {
            // Aplicar el filtro del usuario directamente en la llamada
            const filtersWithUser = {
                ...filters,
                filter_filters: { 
                    ...filters.filter_filters, 
                    user: user.id 
                }
            };
            
            const response = await userHasDietService.listUserHasDiets(filtersWithUser);
            return response.getResponseData() as DietsApiResponse;
        }
        return null;
    }, [filters, user?.id]));

    /**
     * Delete a diet with the given ID.
     *
     * @param {string} id - The ID of the diet to be deleted.
     */
    const deleteDiet = async (id: string) => {
        let response = (await userHasDietService.deleteUserHasDiet(id)).getResponseData();
        if (response.success) {
            toast.success("Dieta eliminada correctamente");
            refetch();
        }
    };
    const toggleUserHasDiet = async (id: string) => {
        try {
            const response = await new UserHasDietService().toogleUserHasDiet(id);
            const responseData = response.getResponseData();
            if (responseData.success) {
                toast.success("Estado de la dieta actualizado correctamente");
            } else {
                toast.error("Error al actualizar el estado de la dieta");
            }
        } catch (error: any) {
            toast.error("Error al comunicarse con el servidor");
        } finally {
            await refetch();
        }
    };

    return (
        <Fragment>
            <SubHeader>
                <SubHeaderLeft>
                    <Fragment>
                        <CardTitle>Listado de Dietas asignadas</CardTitle>
                        {userCan("create", "diets") &&
                            <>
                                <SubheaderSeparator />
                                <Button color="light" icon="Add" isLight onClick={() => { navigate("create") }}>
                                    Crear Dieta
                                </Button>
                            </>
                        }
                        <SubheaderSeparator />
                    </Fragment>
                </SubHeaderLeft>
                <SubHeaderRight>
                    <DietsFilters filters={filters} updateFilters={updateFilters} resetFilters={resetFilters} />
                    <Button 
                        color="warning" 
                        icon="LocalDining" 
                        className="px-4 py-2 fw-bold text-dark shadow-sm"
                        onClick={() => { navigate("/user-diets/daily-intake") }}>
                        Ingesta diaria
                    </Button>
                </SubHeaderRight>
            </SubHeader>
            <Page container="fluid">
                <Card stretch={false}>
                    {error && <ErrorMessage />}

                    {(data && data.diets)
                        ? (
                            <CustomTable
                                data={data?.diets ? data.diets : null}
                                pagination={true}
                                paginationData={{ 
                                    pageSize: filters.limit,
                                    currentPage: filters.page,
                                    pageCount: (data as DietsApiResponse) ? data.lastPage : 1,
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
                                                <div className="text-center cursor-pointer name-link fw-bold" onClick={() => navigate(`${dietMenu.diets.path}/${element.diet.id}/view`)}>
                                                    {element.diet.name}
                                                </div>
                                            );
                                        },
                                    },
                                    {
                                        name: "Descripción",
                                        keyValue: "description",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        className: "text-center",
                                        render: (element: any) => {
                                            return (
                                                <div className="d-flex justify-content-center text-center" >
                                                    {element.diet.description || "N/A"}
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
                                            const creator = element.diet.user;
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
                                                        {moment(element.diet.createdAt.date).format(
                                                            "DD-MM-YYYY"
                                                        ) ?? "N/A"}
                                                    </span>
                                                </div>
                                            );
                                        },
                                    },
                                    {
                                        name: "En curso",
                                        keyValue: "selectedDiet",
                                        sortable: true,
                                        sortColumn: updateFilterOrder,
                                        className: "text-center",
                                        render: (element: any) => {
                                            return (
                                                <div className={"text-center"} key={element.id}>
                                                    <button
                                                        className={`btn btn-sm px-3 py-1 fw-bold border-0 ${
                                                            element.selectedDiet 
                                                                ? "btn-success shadow-sm" 
                                                                : "btn-outline-secondary text-muted"
                                                        }`}
                                                        onClick={() => toggleUserHasDiet(element.id)}
                                                        style={{
                                                            minWidth: '80px',
                                                            borderRadius: '20px',
                                                            transition: 'all 0.2s ease-in-out'
                                                        }}
                                                    >
                                                        <span className="d-flex align-items-center justify-content-center">
                                                            <i className={`bi ${element.selectedDiet ? 'bi-check-circle-fill' : 'bi-circle'} me-1`}></i>
                                                            {element.selectedDiet ? "Activa" : "Inactiva"}
                                                        </span>
                                                    </button>
                                                </div>
                                            );
                                        },
                                    },
                                    { name: "Acciones", className: "min-w-100px text-end", isActionCell: true },
                                ]}
                                actions={[
                                    {
                                        title: "Editar",
                                        buttonType: "icon",
                                        iconColor: "text-info",
                                        iconPath: "/media/icons/duotune/general/gen055.svg",
                                        additionalClasses: "text-primary",
                                        description: "Editar rutina",
                                        hide: (item: any) => {
                                        if (!userCan('edit', 'diets')) return true;
                                        if (isAdmin || isSuperAdmin) return false;
                                        const currentUserId = user?.id;

                                        return !(item.diet.user && item.diet.user.id === currentUserId);
                                    },
                                        callback: (item: any) => { navigate(`${item.diet.id}/edit`) },
                                    },
                                    {
                                        title: "Eliminar",
                                        buttonType: "icon",
                                        iconColor: "text-danger",
                                        iconPath: "/media/icons/duotune/general/gen027.svg",
                                        additionalClasses: "text-danger",
                                        description: "Eliminar dieta",
                                        hide: (item: any) => {
                                        if (!userCan('delete', 'diets')) return true;
                                        if (isAdmin || isSuperAdmin) return false;
                                        const currentUserId = user?.id;
                                        return !(item.diet.user && item.diet.user.id === currentUserId);
                                    },
                                        callback: (item: any) => {
                                            handleConfirmationAlert({
                                                title: "Eliminar dieta",
                                                text: "Esta acción eliminará esta dieta de forma irreversible. ¿Estás seguro de que quieres eliminar esta dieta?",
                                                icon: "warning",
                                                onConfirm: () => { deleteDiet(item.id) },
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
        </Fragment>
    );
};

export default UserHasDietsList;