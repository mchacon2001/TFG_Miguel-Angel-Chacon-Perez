import { Fragment, useCallback, useContext } from "react";
import { useNavigate } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle, } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator, } from "../../../layout/SubHeader/SubHeader";
import FoodsFilters from "./foods-options/FoodsFilters";
import useFetch from "../../../hooks/useFetch";
import { CustomTable } from "../../../components/table/CustomTable";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import moment from "moment";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { Link } from "react-router-dom";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";
import { FoodService } from "../../../services/foods/foodService";
import { FoodApiResponse } from "../../../type/food-type";

const FoodList = () => {

  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);

  const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();

  const [data, loading, error] = useFetch(useCallback(async () => {
    const foodService = new FoodService();
    const response = await foodService.getFood(filters);
    return response.getResponseData() as FoodApiResponse;
  }, [filters]));

  /**
   * Deletes a user with the given ID.
   *
   * @param {string} id - The ID of the user to be deleted.
   */
  const deleteFood = async (id: string) => {
    let response = (await new FoodService().deleteFood(id)).getResponseData();
    if (response.success) {
      toast.success("Usuario eliminado correctamente");
      updateFilters({ ...filters });
    }
  };

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Fragment>
            <CardTitle>Listado de Alimentos</CardTitle>
            {userCan("create", "food") &&
              <>
                <SubheaderSeparator />
                <Button color="light" icon="Add" isLight onClick={() => { navigate("create") }}>
                  Añadir Alimento
                </Button>
              </>
            }
          </Fragment>
        </SubHeaderLeft>
        <SubHeaderRight>
          <FoodsFilters updateFilters={updateFilters} filters={filters} resetFilters={resetFilters} />
        </SubHeaderRight>
      </SubHeader>

      <Page container="fluid">
        <Card stretch={false}>
          {error && <ErrorMessage error={error} />}

          {data && data.food
            ? (
              <CustomTable
                data={data?.food ? data.food : null}
                pagination={true}
                paginationData={{
                  pageSize: filters.limit,
                  currentPage: filters.page,
                  pageCount: (data as FoodApiResponse) ? data.lastPage : 1,
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
                        <div className="text-center fw-bold">
                          {element.name}
                        </div>
                      );
                    },
                  },
                  {
                    name: "Calorias",
                    keyValue: "calories",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center text-center">
                          {element.calories ?? "N/A"}
                        </div>
                      );
                    },
                  },
                                  {
                    name: "Proteinas",
                    keyValue: "proteins",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center text-center">
                          {element.proteins ?? "N/A"}
                        </div>
                      );
                    },
                  },
                                  {
                    name: "Carbohidratos",
                    keyValue: "carbohydrates",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center text-center">
                          {element.carbs ?? "N/A"}
                        </div>
                      );
                    },
                  },
                                  {
                    name: "Grasas",
                    keyValue: "fats",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center text-center">
                          {element.fats ?? "N/A"}
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
                        {element.user ? element.user.name === "SuperAdmin" ? "BrainyGym" : element.user.name === "Admin" ? "BrainyGym": element.user.name : 'N/A'}
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
                            {moment(element.createdAt).format("DD-MM-YYYY") ?? "N/A"}
                          </span>
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
                    description: "Editar alimento",
                    hide: () => !userCan('edit', 'food'),
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
                    description: "Eliminar alimento",
                    hide: () => !userCan('delete', 'food'),
                    callback: (item: any) => {
                      handleConfirmationAlert({
                        title: "Eliminar alimento",
                        text: "¿Estás seguro de que deseas eliminar el alimento?",
                        icon: "warning",
                        onConfirm: () => { deleteFood(item.id) },
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

export default FoodList;