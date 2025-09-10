import { useNavigate, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { useCallback, useState } from "react";
import useFetch from "../../../hooks/useFetch";
import CategoryForm from "../RoutineCategoryForm";
import Modal, { ModalBody, ModalHeader } from "../../../components/bootstrap/Modal";
import { CardTitle } from "../../../components/bootstrap/Card";
import { RoutineService } from "../../../services/routines/routineService";
import { RoutineCategory, EditCategoryFieldsModel } from "../../../type/routine-type";
import { Category } from "../../../components/icon/material-icons";
import Button from "../../../components/bootstrap/Button";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";

const RoutineCategoryEdit = () => {

    const { id = '' } = useParams<{ id: string }>();
    const navigate = useNavigate();
    const { handleErrors } = useHandleErrors();
    const routineService = new RoutineService();

    const [loading, setLoading] = useState<boolean>(false);

    const [entity] = useFetch(useCallback(async () => {
        const response = await routineService.getCategoryById(id as string);
        return response.getResponseData() as RoutineCategory;
    }, [id]));

    const handleUpdate = async (values: EditCategoryFieldsModel) => {
        setLoading(true);

        values.id = id;

        try {
            let response = (await routineService.editRoutineCategory(values)).getResponseData();
            if (response.success) {
                toast.success(response.message);
                navigate(-1);
            } else {
                handleErrors(response);
            }
        } catch (error: any) {
            toast.error('Error al editar la categoría');
        } finally {
            setLoading(false);
        }
    };

    const getContent = () => {
        if (loading) return <Loader height="20vh" />;

        if (entity !== null) {
            const data = {
                ...entity,
                routineCategoryId: entity.id || id,
                name: entity.name || '',
                description: entity.description || '',
            };

            return <CategoryForm isLoading={loading} submit={handleUpdate} data={data} />;
        }
    };

    return (
        <>
            <Modal isOpen={true} setIsOpen={() => (true)} size='md' titleId='Editar categoría'>
                <ModalHeader className='ms-2 p-4 gap-4'>
                    <Category fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
                    <CardTitle className="fs-3">Editar categoría de Rutina</CardTitle>
                    <Button className='btn-close fs-5 p-4' onClick={() => navigate(-1)} />
                </ModalHeader>
                <hr className="mt-0" />
                <ModalBody className='px-4'>
                    <>{getContent()}</>
                </ModalBody>
            </Modal>
        </>
    )
}

export default RoutineCategoryEdit;