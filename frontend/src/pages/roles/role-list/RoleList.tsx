import { Fragment, useCallback, useContext, useState } from "react";
import Button from "../../../components/bootstrap/Button";
import Card, { CardBody, CardTitle, } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { RoleService } from "../../../services/auth/roleService";
import { RolesApiResponse } from "../../../type/role-type";
import useFetch from "../../../hooks/useFetch";
import { useNavigate } from "react-router-dom";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import RoleCreateModal from "../role-create/RoleCreateModal";
import { CustomTable } from "../../../components/table/CustomTable";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import StatusDropdown from "../../../components/StatusDropdown";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";

const RoleList = () => {

  const { userCan } = useContext(PrivilegeContext);
  const { handleErrors } = useHandleErrors();
  const navigate = useNavigate();

  const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();

  const [roleCreationModal, setRoleCreationModal] = useState(false);
  const [changingStatus, setChangingStatus] = useState<string[]>([]);

  const [data, loadingRole, errorRole, refetch] = useFetch(useCallback(async () => {
    const roleService = new RoleService();
    const response = await roleService.getRoles(filters);
    return response.getResponseData() as RolesApiResponse;
  }, [filters]));


  const handleDelete = async (id: string) => {
    try {
      const response = await (await (new RoleService()).deleteRole(id)).getResponseData();
      if (response.success) {
        refetch();
        setTimeout(() => {
          toast.success("Rol eliminado correctamente");
        }, 100);
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      handleErrors(error);
    }
  };

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <CardTitle>Listado de Roles</CardTitle>
          <SubheaderSeparator />
          {userCan('list', 'roles', true) &&
            <Button color="light" icon="Add" isLight onClick={() => { setRoleCreationModal(true) }}>
              Añadir Rol
            </Button>
          }
        </SubHeaderLeft>
      </SubHeader>

      <Page container="fluid">
        <Card stretch={true}>
          <CardBody>
            <Fragment>
              {data
                ? (
                  <CustomTable
                    data={data ? data.roles : null}
                    pagination={true}
                    defaultLimit={filters.limit || 50}
                    defaultOrder={filters.filter_order || undefined}
                    paginationData={{
                      pageSize: filters.limit,
                      currentPage: filters.page,
                      pageCount: (data as RolesApiResponse) ? data.lastPage : 1,
                      totalCount: data.totalRegisters,
                      handlePagination: updatePage,
                      handlePerPage: updatePageSize,
                    }}
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
                            <div className="d-flex justify-content-center cursor-pointer text-primary fw-bold" onClick={() => { navigate(`${element.id}/edit`) }}>
                              {element.name}
                            </div>
                          )
                        },
                      },
                      {
                        name: "Descripción",
                        keyValue: "description",
                        sortable: true,
                        sortColumn: updateFilterOrder,
                        className: "text-center",
                        cellClassName: "text-center"
                      },
                      { name: "Acciones", className: "min-w-100px text-end", isActionCell: true }
                    ]}
                    actions={[
                      {
                        title: "Editar",
                        buttonType: 'icon',
                        iconColor: 'text-info',
                        iconPath: '/media/icons/duotune/general/gen055.svg',
                        additionalClasses: 'text-primary',
                        description: "Editar rol",
                        callback: (item: any) => {
                          if (userCan('edit', 'roles', true)) navigate(`${item.id}/edit`);
                        },
                      },

                      {
                        title: "Eliminar",
                        buttonType: 'icon',
                        iconColor: 'text-danger',
                        iconPath: '/media/icons/duotune/general/gen027.svg',
                        additionalClasses: 'text-danger',
                        description: "Eliminar rol",
                        callback: (item: any) => {
                          if (userCan('delete', 'roles', true)) {
                            handleConfirmationAlert({
                              title: "Eliminar rol",
                              text: "¿Estás seguro de que deseas eliminar el rol?",
                              icon: "warning",
                              onConfirm: () => {
                                handleDelete(item.id);
                              }
                            })
                          }
                        },
                      },
                    ]}
                  />
                )
                : !errorRole && <Loader />
              }
            </Fragment>
          </CardBody>
        </Card>

        {roleCreationModal && (
          <RoleCreateModal
            isOpen={roleCreationModal}
            setIsOpen={setRoleCreationModal}
            handleSuccessCreation={() => {
              toast.success("Rol creado correctamente");
              setRoleCreationModal(false);
              refetch();
            }}
          />
        )}
      </Page>
    </Fragment>
  );
};

export default RoleList;