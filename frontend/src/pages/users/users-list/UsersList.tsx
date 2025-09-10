import { Fragment, useCallback, useContext, useState } from "react";
import { useNavigate } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { UserService } from "../../../services/users/userService";
import UsersFilters from "./users-options/UsersFilters";
import useFetch from "../../../hooks/useFetch";
import { UsersApiResponse } from "../../../type/user-type";
import AsyncImg from "../../../components/AsyncImg";
import Badge from "../../../components/bootstrap/Badge";
import { CustomTable } from "../../../components/table/CustomTable";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import moment from "moment";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { Link } from "react-router-dom";
import { RestorePermissionsComponent } from "../../../components/permissions/RestorePermissionsButton";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";

const UsersList = () => {

  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();


  const [data, loading, error] = useFetch(useCallback(async () => {
    const userService = new UserService();
    const response = await userService.getUsers(filters);
    return response.getResponseData() as UsersApiResponse;
  }, [filters]));

  /**
   * Deletes a user with the given ID.
   *
   * @param {string} id - The ID of the user to be deleted.
   */
  const deleteUser = async (id: string) => {
    let response = (await new UserService().deleteUser(id)).getResponseData();
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
            <CardTitle>Listado de Usuarios</CardTitle>
            {userCan("create", "user") && (<>
              <SubheaderSeparator />
              <Button color="light" icon="PersonAdd" isLight onClick={() => { navigate("create") }}>
                Añadir Usuario
              </Button>
            </>
            )}
          </Fragment>
        </SubHeaderLeft>
        <SubHeaderRight>
          <UsersFilters updateFilters={updateFilters} filters={filters} resetFilters={resetFilters} />
        </SubHeaderRight>
      </SubHeader>

      <Page container="fluid">
        <Card stretch={false}>
          {error && <ErrorMessage />}

          {(data && data.users)
            ? (
              <CustomTable
                data={data?.users ? data.users : null}
                pagination={true}
                paginationData={{
                  pageSize: filters.limit,
                  currentPage: filters.page,
                  pageCount: (data as UsersApiResponse) ? data.lastPage : 1,
                  totalCount: data.totalRegisters,
                  handlePagination: updatePage,
                  handlePerPage: updatePageSize,
                }}
                defaultLimit={filters.limit || 50}
                defaultOrder={filters.filter_order || undefined}
                className={"table table-hover"}
                columns={[
                  {
                    name: "",
                    keyValue: "img",
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center">
                          <Link to={`/users/${element.id}/profile`}>
                            <div >
                              <AsyncImg height="50px" width="50px" styles="rounded-circle" defaultAvatarSize={40}
                                id={
                                  element.profileImg
                                    ? element.profileImg.id
                                    : null
                                }
                              />
                            </div>
                          </Link>
                        </div>
                      );
                    },
                  },
                  {
                    name: "Nombre",
                    keyValue: "name",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <Link to={`/users/${element.id}/profile`} style={{ textDecoration: 'none' }}>
                          <div className="d-flex justify-content-center text-center fw-bold">
                            {element.name}
                          </div>
                        </Link>
                      );
                    },
                  },
                  {
                    name: "Email",
                    keyValue: "email",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    cellClassName: "text-center",
                  },
                  {
                    name: "Fecha de creación",
                    keyValue: "created_at",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className={"text-center"}>
                          <span className={"text-muted"}>
                            {moment(element.createdAt.date).format(
                              "DD-MM-YYYY HH:mm"
                            ) ?? "N/A"}
                          </span>
                        </div>
                      );
                    },
                  },
                  {
                    name: "Último acceso",
                    keyValue: "last_login_at",
                    sortable: true,
                    sortColumn: updateFilterOrder,
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className={"text-center"}>
                          <span className={"text-muted"}>
                            {(element.lastLogin?.date &&
                              moment(element.lastLogin?.date).format(
                                "DD-MM-YYYY HH:mm"
                              )) ?? "N/A"}
                          </span>
                        </div>
                      );
                    },
                  },
                  {
                    name: "Rol",
                    keyValue: "role",
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className="d-flex justify-content-center">
                          {element.userRoles.map((userRole: any) => (
                            <Badge
                              key={userRole.id}
                              color={"primary"}
                              isLight={true}
                              className="px-3 py-2"
                              rounded={1}
                            >
                              {userRole.role.name}
                            </Badge>
                          ))}
                        </div>
                      );
                    },
                  },
                  {
                    name: "Permisos",
                    keyValue: "role",
                    sortable: false,
                    sortColumn: updateFilterOrder,
                    isVisible: userCan("list", "users"),
                    className: "text-center",
                    render: (element: any) => {
                      return (
                        <div className={"text-center"}>
                          <RestorePermissionsComponent
                            userId={element.id}
                            size={"sm"}
                          ></RestorePermissionsComponent>
                        </div>
                      );
                    },
                  },

                  {
                    name: "Acciones",
                    className: "min-w-100px text-end",
                    isActionCell: true,
                  },
                ]}
                actions={[
                  {
                    title: "Editar",
                    buttonType: "icon",
                    iconColor: "text-info",
                    iconPath: "/media/icons/duotune/general/gen055.svg",
                    additionalClasses: "text-primary",
                    description: "Editar perfil del usuario",
                    hide: (item: any) => {
                      // Ocultar si no tiene permisos O si el usuario es superadministrador
                      const isSuperAdmin = item.userRoles?.some((userRole: any) => 
                        userRole.role?.name?.toLowerCase() === 'superadministrador' || 
                        userRole.role?.id === 1
                      );
                      return !userCan('edit', 'user') || isSuperAdmin;
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
                    description: "Eliminar usuario",
                    hide: (item: any) => {
                      // Ocultar si no tiene permisos O si el usuario es superadministrador
                      const isSuperAdmin = item.userRoles?.some((userRole: any) => 
                        userRole.role?.name?.toLowerCase() === 'superadministrador' || 
                        userRole.role?.id === 1
                      );
                      return !userCan('delete', 'user') || isSuperAdmin;
                    },
                    callback: (item: any) => {
                      handleConfirmationAlert({
                        title: "Eliminar usuario",
                        text: "¿Estás seguro de que deseas eliminar el usuario?",
                        icon: "warning",
                        onConfirm: () => { deleteUser(item.id) },
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

export default UsersList;