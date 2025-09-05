import { Fragment, useState } from "react";
import { useNavigate } from "react-router-dom";
import { ExerciseService } from "../../../services/exercises/exerciseService";
import { toast } from "react-toastify";
import Modal, { ModalBody, ModalHeader } from "../../../components/bootstrap/Modal";
import CategoryForm from "../ExerciseCategoryForm";
import { CardTitle } from "../../../components/bootstrap/Card";
import { exerciseCategoryMenu } from "../../../menu";
import { Category } from "../../../components/icon/material-icons";
import Button from "../../../components/bootstrap/Button";

const ExerciseCategoryCreate = () => {

    const navigate = useNavigate();

    const [modal, setModal] = useState(false);
    const [loading, setLoading] = useState<boolean>(false);

    const handleCreation = async (values: any) => {
        try {
            setLoading(true)
            let response = await (await (new ExerciseService()).createExerciseCategory(values)).getResponseData();
            if (response.success) {
                toast.success(response.message);
                navigate(exerciseCategoryMenu.exerciseCategories.path, { replace: true })
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
                    <CardTitle className="fs-3">Crear categoría de Ejercicio</CardTitle>
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

export default ExerciseCategoryCreate;