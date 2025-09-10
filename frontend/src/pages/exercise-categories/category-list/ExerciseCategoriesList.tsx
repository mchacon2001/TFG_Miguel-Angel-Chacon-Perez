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
import { ExerciseService } from "../../../services/exercises/exerciseService";
import { ExerciseCategoriesApiResponse } from "../../../type/exercise-type";
import CategoryFilters from "./categories-options/CategoriesFilters";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";

const ExerciseCategoriesList = () => {

    const { userCan } = useContext(PrivilegeContext);
    const navigate = useNavigate();
    const exerciseService = new ExerciseService();

    const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters, checkIfUrlHasFilters } = useFiltersPR();

    const [data, loading, error] = useFetch(useCallback(async () => {
        const response = await exerciseService.getExercisesCategories(filters);
        return response.getResponseData() as ExerciseCategoriesApiResponse;
    }, [filters]));

    /**
     * Deletes a user with the given ID.
     *
     * @param {string} id - The ID of the user to be deleted.
     */
    const deleteCategory = async (id: string) => {
        let response = (await exerciseService.deleteExerciseCategory(id)).getResponseData();
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
                        <CardTitle>Listado de Categorías de Ejercicios</CardTitle>
                        {userCan("create", "exercises") &&
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
                    <CategoryFilters filters={filters} updateFilters={updateFilters} resetFilters={resetFilters} />
                </SubHeaderRight>
            </SubHeader>

            <Page container="fluid">
                <Card stretch={false}>
                    {error && <ErrorMessage error={error} />}

                    {data && data.exerciseCategories
                        ? (
                            <CustomTable
                                data={data?.exerciseCategories ? data.exerciseCategories : null}
                                pagination={true}
                                paginationData={{
                                    pageSize: filters.limit,
                                    currentPage: filters.page,
                                    pageCount: (data as ExerciseCategoriesApiResponse) ? data.lastPage : 1,
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
                                        hide: () => !userCan('edit', 'exercises'),
                                        callback: (item: any) => { navigate(`${item.id}/edit`) },
                                    },
                                    {
                                        title: "Eliminar",
                                        buttonType: "icon",
                                        iconColor: "text-danger",
                                        iconPath: "/media/icons/duotune/general/gen027.svg",
                                        additionalClasses: "text-danger",
                                        description: "Eliminar categoría",
                                        hide: () => !userCan('delete', 'exercises'),
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

export default ExerciseCategoriesList;