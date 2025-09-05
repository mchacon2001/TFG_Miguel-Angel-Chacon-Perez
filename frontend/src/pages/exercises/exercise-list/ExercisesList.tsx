import { Fragment, useCallback, useContext } from "react";
import { useNavigate } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle, } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator, } from "../../../layout/SubHeader/SubHeader";
import ExercisesFilters from "./exercises-options/ExercisesFilters";
import useFetch from "../../../hooks/useFetch";
import { CustomTable } from "../../../components/table/CustomTable";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import moment from "moment";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { Link } from "react-router-dom";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import { ExerciseService } from "../../../services/exercises/exerciseService";
import { ExercisesApiResponse } from "../../../type/exercise-type";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { FixNumber } from "../../../utils/fixNumber";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";


const ExercisesList = () => {

  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const user = useSelector((state: RootState) => state.auth.user);
  const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();

  const [data, loading, error] = useFetch(useCallback(async () => {
    const exerciseService = new ExerciseService();
    const response = await exerciseService.getExercises(filters);
    return response.getResponseData() as ExercisesApiResponse;
  }, [filters]));

  /**
   * Deletes a user with the given ID.
   *
   * @param {string} id - The ID of the user to be deleted.
   */
  const deleteExercise = async (id: string) => {
    let response = (await new ExerciseService().deleteExercise(id)).getResponseData();
    if (response.success) {
      toast.success("Usuario eliminado correctamente");
      updateFilters({ ...filters });
    }
  };

  const isAdmin = userIsAdmin() || false;
  const isSuperAdmin = userIsSuperAdmin() || false;

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Fragment>
            <CardTitle>Listado de Ejercicios</CardTitle>
            {userCan("create", "exercises") &&
              <>
                <SubheaderSeparator />
                <Button color="light" icon="Add" isLight onClick={() => { navigate("create") }}>
                  Añadir Ejercicio
                </Button>
              </>
            }
          </Fragment>
        </SubHeaderLeft>
        <SubHeaderRight>
          <ExercisesFilters updateFilters={updateFilters} filters={filters} resetFilters={resetFilters} />
        </SubHeaderRight>
      </SubHeader>

      <Page container="fluid">
        <Card stretch={false}>
          {error && <ErrorMessage error={error} />}

          {data && data.exercises
            ? (
              <CustomTable
                data={data?.exercises ? data.exercises : null}
                pagination={true}
                paginationData={{
                  pageSize: filters.limit,
                  currentPage: filters.page,
                  pageCount: (data as ExercisesApiResponse) ? data.lastPage : 1,
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
                        <Link to={`/exercises/${element.id}/view`} style={{ textDecoration: 'none' }}>
                          <div className="text-center cursor-pointer name-link fw-bold">
                            {element.name}
                          </div>
                        </Link>
                      );
                    },
                  },
                  {
                    name: "Categoría",
                    keyValue: "exercise_category",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center text-center">
                          {element.exerciseCategories.name}
                        </div>
                      );
                    },
                  },
                  ...(isAdmin || isSuperAdmin
                    ? [{
                        name: "Creador",
                        keyValue: "user",
                        sortable: true,
                        sortColumn: updateFilterOrder,
                        className: "text-center",
                        render: (element: any) => {
                          return (
                            <div className="d-flex justify-content-center text-center">
                              {element.user ? element.user.name === "SuperAdmin" ? "BrainyGym" : element.user.name === "Admin" ? "BrainyGym": element.user.name : 'N/A'}
                            </div>
                          );
                        },
                      }]
                    : []
                  ),
                  ...(isAdmin || isSuperAdmin
                    ? [{
                        name: "Fecha de creación",
                        keyValue: "created_at",
                        sortable: true,
                        sortColumn: updateFilterOrder,
                        className: `text-center`,
                        render: (element: any) => {
                          return (
                            <div className={"text-center"}>
                              <span className={"text-muted"}>
                                {moment(element.createdAt.date).format("DD-MM-YYYY") ?? "N/A"}
                              </span>
                            </div>
                          );
                        },
                      }]
                    : []
                  ),
                  { name: "Acciones", className: "min-w-100px text-end", isActionCell: true },
                ]}
                actions={[
                  {
                    title: "Editar",
                    buttonType: "icon",
                    iconColor: "text-info",
                    iconPath: "/media/icons/duotune/general/gen055.svg",
                    additionalClasses: "text-primary",
                    description: "Editar ejercicio",
                    hide: (item: any) => {
                      if (!userCan('edit', 'exercises')) return true;
                      if (isAdmin || isSuperAdmin) return false;
                      const currentUserId = user?.id;
                      return !(item.user && item.user.id === currentUserId);
                    },
                    callback: (item: any) => {
                      navigate(`${item.id}/edit`);
                    },
                  },
                  {
                    title: "Eliminar",
                    buttonType: "icon",
                    iconColor: "text-danger",
                    iconPath: "/media/icons/duotune/general/gen027.svg",
                    additionalClasses: "text-danger",
                    description: "Eliminar exercisee",
                    hide: (item: any) => {
                      if (!userCan('delete', 'exercises')) return true;
                      if (isAdmin || isSuperAdmin) return false;
                      const currentUserId = user?.id;
                      return !(item.user && item.user.id === currentUserId);
                    },
                    callback: (item: any) => {
                      handleConfirmationAlert({
                        title: "Eliminar ejercicio",
                        text: "¿Estás seguro de que deseas eliminar el ejercicio?",
                        icon: "warning",
                        onConfirm: () => { deleteExercise(item.id) },
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

export default ExercisesList;