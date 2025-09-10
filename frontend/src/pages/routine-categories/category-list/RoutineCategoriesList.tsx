import { Fragment, useCallback, useContext } from "react";
import { useNavigate } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator, } from "../../../layout/SubHeader/SubHeader";
import useFetch from "../../../hooks/useFetch";
import { CustomTable } from "../../../components/table/CustomTable";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import moment from "moment";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import CategoryFilters from "./categories-options/CategoriesFilters";
import { RoutineService } from "../../../services/routines/routineService";
import { RoutinesApiResponse } from "../../../type/routine-type";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";

const RoutineCategoriesList = () => {

    const { userCan } = useContext(PrivilegeContext);
    const navigate = useNavigate();
    const routineService = new RoutineService();

    const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();

    const [data, loading, error] = useFetch(useCallback(async () => {
        const response = await routineService.getRoutineCategories(filters);
        return response.getResponseData() as RoutinesApiResponse;
    }, [filters]));

    /**
     * Deletes a category with the given ID.
     *
     * @param {string} id - The ID of the category to be deleted.
     */
    
    const deleteCategory = async (id: string) => {
        let response = (await routineService.deleteRoutineCategory(id)).getResponseData();
        if (response.success) {
            toast.success("Categoría eliminada correctamente");
            updateFilters({ ...filters });
        }
    };

    return (
        <Fragment>
            <SubHeader>
                <SubHeaderLeft>
                    <Fragment>
                        <CardTitle>Listado de Categorías de Rutinas</CardTitle>
                        {userCan("create", "routines") &&
                            <>
                                <SubheaderSeparator />
                                <Button color="light" icon="Add" isLight onClick={() => { navigate("create") }}>
                                    Añadir Categoría
                                </Button>
                            </>
                        }
                    </Fragment>
                </SubHeaderLeft>
                <SubHeaderRight>
                    <CategoryFilters updateFilters={updateFilters} filters={filters} resetFilters={resetFilters} />
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
                                                <div className="d-flex justify-content-center text-center">
                                                    {element.name}
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
                                        cellClassName: "text-center",
                                    },
                                    {
                                        name: "Creador",
                                        keyValue: "user",
                                        className: "text-center",
                                        render: (element: any) => {
                                            return (
                                                <div className="d-flex justify-content-center text-center">
                                                    {element.user ? element.user.name === "SuperAdmin" ? "BrainyGym" : element.user.name === "Admin" ? "BrainyGym": element.user.name : 'N/A'}
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
                                        description: "Editar categoría",
                                        hide: () => !userCan('edit', 'routines'),
                                        callback: (item: any) => { navigate(`${item.id}/edit`) },
                                    },
                                    {
                                        title: "Eliminar",
                                        buttonType: "icon",
                                        iconColor: "text-danger",
                                        iconPath: "/media/icons/duotune/general/gen027.svg",
                                        additionalClasses: "text-danger",
                                        description: "Eliminar categoría",
                                        hide: () => !userCan('delete', 'routines'),
                                        callback: (item: any) => {
                                            handleConfirmationAlert({
                                                title: "Eliminar categoría",
                                                text: "¿Estás seguro de que deseas eliminar la categoría?",
                                                icon: "warning",
                                                onConfirm: () => { deleteCategory(item.id) },
                                            });
                                        },
                                    },
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

export default RoutineCategoriesList;