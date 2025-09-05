import { FC, useCallback, Fragment, useState } from "react";
import { useFormik } from "formik";
import { toast } from "react-toastify";
import Modal, { ModalHeader, ModalBody, ModalFooter, ModalTitle } from "../../../components/bootstrap/Modal";
import Button from "../../../components/bootstrap/Button";
import SearchableSelect from "../../../components/SearchableSelect";
import useFetch from "../../../hooks/useFetch";
import useFilters from "../../../hooks/useFilters";
import { UserService } from "../../../services/users/userService";
import { UserApiResponse } from "../../../type/user-type";
import Spinner from "../../../components/bootstrap/Spinner";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import { DietService } from "../../../services/diets/dietService";

interface AssignUserDietModalProps {
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
  dietId: string;
  userDiets: any[];
}

const AssignUserDietModal: FC<AssignUserDietModalProps> = ({
  isOpen,
  setIsOpen,
  dietId,
  userDiets,
}) => {
  const { filters } = useFilters({}, [], 1, 1000);
  const [loading, setLoading] = useState(false);

  const [users, fetchingUsers, userError] = useFetch(
    useCallback(async () => {
      let auxfilters = { ...filters };
      auxfilters.limit = 99999;
      const response = await new UserService().getUsers(auxfilters);
      return response.getResponseData() as UserApiResponse;
    }, [filters])
  );

  const getUsersList = () => {
    if (!users || !users.users) return [];
    return users.users
      .filter((user: any) =>
        !user.userRoles?.some((role: any) => role.role.id === 1 || role.role.id === 2)
      )
      .map((user: any) => ({
        value: user.id,
        label: `${user.name}`,
      }));
  };

  const getAssignedUserIds = () => {
    if (!userDiets) return [];
    return userDiets
      .filter((ud: any) => {
        if (ud.diet && ud.diet.id && ud.user && ud.user.id) {
          return ud.diet.id === dietId;
        }
        return ud.id;
      })
      .map((ud: any) => ud.user?.id || ud.id)
      .filter(Boolean);
  };

  // Combina los usuarios filtrados con los asignados (aunque no estén en users)
  const getAllOptions = () => {
    const options = getUsersList();
    const assignedIds = getAssignedUserIds();
    // Añade los asignados que no estén en la lista de usuarios filtrados
    assignedIds.forEach((id) => {
      if (!options.some((opt : any) => opt.value === id)) {
        // Busca el usuario en userDiets para obtener el nombre
        const ud = userDiets.find(
          (ud: any) => (ud.user?.id || ud.id) === id
        );
        const label = ud?.user?.name || ud?.name || id;
        options.push({ value: id, label });
      }
    });
    return options;
  };

  // ids seleccionados (siempre los asignados a la dieta)
  const initialUserIds = getAssignedUserIds();

  const formik = useFormik({
    initialValues: {
      dietId,
      userIds: initialUserIds,
    },
    enableReinitialize: true,
    onSubmit: async (values) => {
      setLoading(true);
      try {
        const response = await new DietService().assignUserToDiet(values.dietId, values.userIds);
        const result = response.getResponseData();
        if (result.success) {
          toast.success("Usuarios asignados a la dieta correctamente");
        } else {
          toast.error("Error al asignar usuarios a la dieta");
        }
      } catch (error) {
        toast.error("Error al asignar usuarios a la dieta");
      } finally {
        setLoading(false);
        setIsOpen(false);
      }
    },
  });

  const getContent = () => {
    if (fetchingUsers)
      return (
        <div className="text-center">
          <Spinner />
        </div>
      );
    if (userError) return <div>Error al cargar usuarios</div>;

    const allOptions = getAllOptions();

    return (
      <Fragment>
        <div className="row g-4">
          <FormGroup requiredInputLabel label="Usuarios" className="col-12">
            <SearchableSelect
              isSearchable
              isMulti
              name="userIds"
              options={allOptions}
              value={allOptions.filter((opt: any) =>
                formik.values.userIds.includes(opt.value)
              )}
              onChange={(selected: any) => {
                const values = Array.isArray(selected)
                  ? selected.map((opt: any) => opt.value)
                  : [];
                formik.setFieldValue("userIds", values);
              }}
              placeholder="Selecciona usuarios"
            />
          </FormGroup>
        </div>
      </Fragment>
    );
  };

  return (
    <Modal isOpen={isOpen} setIsOpen={setIsOpen} size="md" titleId="asignar_usuarios_dieta">
      <ModalHeader setIsOpen={setIsOpen} className="p-4">
        <ModalTitle id="asignar_usuarios_dieta">Asignar usuarios a la dieta</ModalTitle>
      </ModalHeader>
      <form onSubmit={formik.handleSubmit} autoComplete="off">
        <ModalBody className="px-4">{getContent()}</ModalBody>
        <ModalFooter className="px-4 pb-4">
          <Button type="button" color="secondary" onClick={() => setIsOpen(false)} className="me-2">
            Cancelar
          </Button>
          <Button type="submit" color="primary" isDisable={loading}>
            {loading ? <Spinner isSmall /> : "Asignar"}
          </Button>
        </ModalFooter>
      </form>
    </Modal>
  );
};

export default AssignUserDietModal;
