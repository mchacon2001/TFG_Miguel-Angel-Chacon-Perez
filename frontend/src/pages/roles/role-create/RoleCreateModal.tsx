import { FC, Fragment, useCallback, useState } from "react";
import Button from "../../../components/bootstrap/Button";
import Checks from "../../../components/bootstrap/forms/Checks";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import Input from "../../../components/bootstrap/forms/Input";
import Modal, { ModalBody, ModalFooter, ModalHeader, ModalTitle } from "../../../components/bootstrap/Modal";
import useFetch from "../../../hooks/useFetch";
import { PermissionService } from "../../../services/auth/permissionService";
import Spinner from "../../../components/bootstrap/Spinner";
import { NewRole, Permission, PermissionGroup, PermissionsApiResponse } from "../../../type/role-type";
import Accordion, { AccordionItem } from "../../../components/bootstrap/Accordion";
import Select from "../../../components/bootstrap/forms/Select";
import { useFormik } from "formik";
import { RoleService } from "../../../services/auth/roleService";
import useHandleErrors from "../../../hooks/useHandleErrors";

interface RoleCreateModalProps {
  isOpen: boolean;
  setIsOpen(...args: unknown[]): unknown;
  handleSuccessCreation(): void;
}

const RoleCreateModal: FC<RoleCreateModalProps> = ({ isOpen, setIsOpen, handleSuccessCreation }) => {

  const { handleErrors } = useHandleErrors();

  const [loading, setLoading] = useState(false);
  const [selectedPermissions, setSelectedPermissions] = useState<number[]>([]);
  const [selectAll, setSelectAll] = useState<number[]>([]);

  const fetchPermissions = useCallback(async () => {
    const permissionService = new PermissionService();
    const response = await permissionService.getPermissions();
    return response.getResponseData() as PermissionsApiResponse;
  }, []);

  const [permissions, fetchingPermissions, permissionError] =
    useFetch(fetchPermissions);

  const createRole = async (values: NewRole) => {
    values.permissions = selectedPermissions;

    try {
      setLoading(true);
      let response = await (await new RoleService().createRole(values)).getResponseData();

      if (response.success) {
        handleSuccessCreation();
        setIsOpen(false);
      } else {
        handleErrors(response);
      }
    } catch (e) {
      console.log("error", e);
    } finally {
      setLoading(false);
    }
  };

  const formik = useFormik({
    initialValues: {
      name: "",
      description: "",
      permissions: [],
    },
    onSubmit: (values: NewRole) => {
      createRole(values);
    },
  });

  const getContent = () => {
    if (fetchingPermissions)
      return (
        <div className="text-center">
          {" "}
          <Spinner />{" "}
        </div>
      );

    if (permissionError ) return <div>Error</div>;

    return (
      <Fragment>
        <div className="row g-4">
          <FormGroup requiredInputLabel id="name" label="Nombre" className="col-md-6">
            <Input
              required
              value={formik.values.name}
              onChange={formik.handleChange}
            />
          </FormGroup>
          <FormGroup id="description" label="Breve descripción" className="col-md-6">
            <Input
              id="description"
              value={formik.values.description}
              onChange={formik.handleChange}
            />
          </FormGroup>
        </div>
        <div className="row mt-5">
          {permissions?.map((group: PermissionGroup, index: number) => {
            return (
              <div className="col-lg-3 col-md-6 col-sm-6 mb-5" key={index}>
                <Accordion id={group.name} isFlush activeItemId={group.id}>
                  <AccordionItem id={group.id} title={group.label}>
                    <>
                      <Checks
                        label="Seleccionar todos"
                        value="all"
                        checked={selectAll.includes(group.id)}
                        onChange={() => {
                          const list = group.permissions.map((item: Permission) => item.id);
                          if (selectAll.includes(group.id)) {
                            setSelectAll(selectAll.filter((id: number) => id !== group.id));
                            setSelectedPermissions(selectedPermissions.filter(item => !list.includes(item)));
                          } else {
                            setSelectAll([...selectAll, group.id]);
                            setSelectedPermissions([...selectedPermissions.concat(list)]);
                          }
                        }}
                      />
                    </>
                    {group.permissions.map((permission: Permission, index: number) => {
                      return (
                        <div key={index}>
                          <Checks
                            label={permission.label}
                            value={permission.id}
                            checked={selectedPermissions.includes(permission.id)}
                            onChange={() => {
                              selectedPermissions.includes(permission.id)
                                ? setSelectedPermissions(
                                  selectedPermissions.filter((id: number) => id !== permission.id)
                                )
                                : setSelectedPermissions([...selectedPermissions, permission.id]);
                            }}
                          />
                        </div>
                      );
                    })}
                  </AccordionItem>
                </Accordion>
              </div>
            );
          })}
        </div>
      </Fragment>
    );
  };

  return (
    <Modal isOpen={isOpen} setIsOpen={setIsOpen} size="xl" titleId="Nuevo Rol">
      <ModalHeader setIsOpen={setIsOpen} className="p-4">
        <ModalTitle id="new_role">Nuevo Rol</ModalTitle>
      </ModalHeader>
      <form onSubmit={formik.handleSubmit} autoComplete="off">
        <ModalBody className="px-4">{getContent()}</ModalBody>
        <ModalFooter className="px-4 pb-4">
          <Button icon={"Save"} color='primary' type="submit">
            Guardar Rol
          </Button>
        </ModalFooter>
      </form>
    </Modal>
  );
};

export default RoleCreateModal;