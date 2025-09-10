import { Fragment, useState } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { routineCategoryMenu } from "../../../menu";
import Modal, { ModalBody, ModalHeader } from "../../../components/bootstrap/Modal";
import CategoryForm from "../RoutineCategoryForm";
import { CardTitle } from "../../../components/bootstrap/Card";
import { RoutineService } from "../../../services/routines/routineService";
import { Category } from "../../../components/icon/material-icons";
import Button from "../../../components/bootstrap/Button";

const RoutineCategoryCreate = () => {

    const navigate = useNavigate();

    const [modal, setModal] = useState(false);
    const [loading, setLoading] = useState<boolean>(false);

    const handleCreation = async (values: any) => {
        try {
            setLoading(true)
            let response = await (await (new RoutineService()).createRoutineCategory(values)).getResponseData();
            if (response.success) {
                toast.success(response.message);
                navigate(routineCategoryMenu.routineCategories.path, { replace: true })
            } else {
                toast.error(response.message);
            }
        } catch (error: any) {
            toast.error('Error al crear la categoría');
        } finally {
            setLoading(false);
        }
    };

    return (
        <Fragment>
            <Modal isOpen={true} setIsOpen={setModal} size='md' titleId='Nueva categoría'>
                <ModalHeader className='ms-2 p-4 gap-4'>
                    <Category fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
                    <CardTitle className="fs-3">Crear categoría de Rutina</CardTitle>
                    <Button className='btn-close fs-5 p-4' onClick={() => navigate(-1)} />
                </ModalHeader>
                <hr className="mt-0" />
                <ModalBody className='px-4'>
                    <CategoryForm submit={handleCreation} isLoading={loading} />
                </ModalBody>
            </Modal>
        </Fragment>
    )
}

export default RoutineCategoryCreate;