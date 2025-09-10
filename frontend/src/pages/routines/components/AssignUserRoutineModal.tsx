import { FC, useCallback, Fragment, useState } from "react";
import { useFormik } from "formik";
import Modal, { ModalHeader, ModalBody, ModalFooter, ModalTitle } from "../../../components/bootstrap/Modal";
import Button from "../../../components/bootstrap/Button";
import SearchableSelect from "../../../components/SearchableSelect";
import useFetch from "../../../hooks/useFetch";
import useFilters from "../../../hooks/useFilters";
import { UserService } from "../../../services/users/userService";
import { UserApiResponse } from "../../../type/user-type";
import Spinner from "../../../components/bootstrap/Spinner";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import { RoutineService } from "../../../services/routines/routineService";
import { toast } from "react-toastify";

interface AssignUserRoutineModalProps {
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
  routineId: string;
  userRoutines: any[];
}

const AssignUserRoutineModal: FC<AssignUserRoutineModalProps> = ({
  isOpen,
  setIsOpen,
  routineId,
  userRoutines,
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
    if (!userRoutines) return [];
    return userRoutines
      .filter((ur: any) => {
        if (ur.routine && ur.routine.id && ur.user && ur.user.id) {
          return ur.routine.id === routineId;
        }
        return ur.id;
      })
      .map((ur: any) => ur.user?.id || ur.id)
      .filter(Boolean);
  };

  // Combina los usuarios filtrados con los asignados (aunque no estén en users)
  const getAllOptions = () => {
    const options = getUsersList();
    const assignedIds = getAssignedUserIds();
    // Añade los asignados que no estén en la lista de usuarios filtrados
    assignedIds.forEach((id) => {
      if (!options.some((opt : any) => opt.value === id)) {
        // Busca el usuario en userRoutines para obtener el nombre
        const ur = userRoutines.find(
          (ur: any) => (ur.user?.id || ur.id) === id
        );
        const label = ur?.user?.name || ur?.name || id;
        options.push({ value: id, label });
      }
    });
    return options;
  };

  // ids seleccionados (siempre los asignados a la rutina)
  const initialUserIds = getAssignedUserIds();

  const formik = useFormik({
    initialValues: {
      routineId,
      userIds: initialUserIds,
    },
    enableReinitialize: true,
    onSubmit: async (values) => {
      setLoading(true);
      try {
        const response = await new RoutineService().assignUserToRoutine(values.routineId, values.userIds);
        const result = response.getResponseData();
        if (result.success) {
          toast.success("Usuarios asignados a la rutina correctamente");
        } else {
          toast.error("Error al asignar usuarios a la rutina");
        }
      } catch (error) {
        toast.error("Error al asignar usuarios a la rutina");
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
    <Modal isOpen={isOpen} setIsOpen={setIsOpen} size="md" titleId="asignar_usuarios_rutina">
      <ModalHeader setIsOpen={setIsOpen} className="p-4">
        <ModalTitle id="asignar_usuarios_rutina">Asignar usuarios a la rutina</ModalTitle>
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

export default AssignUserRoutineModal;
