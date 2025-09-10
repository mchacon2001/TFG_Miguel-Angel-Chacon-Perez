import { FC, useContext } from "react";
import * as yup from 'yup';
import { PrivilegeContext } from "../../components/priviledge/PriviledgeProvider";
import { useFormik } from "formik";
import { CardBody, CardFooter } from "../../components/bootstrap/Card";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Select from "../../components/bootstrap/forms/Select";
import Input from "../../components/bootstrap/forms/Input";
import Button from "../../components/bootstrap/Button";
import Spinner from "../../components/bootstrap/Spinner";

interface RoutineCategoryFormProps {
    isLoading: boolean;
    submit: Function;
    data?: any;
}

interface IRoutineCategoryForm {
    routineCategoryId: string;
    name: string;
    description: string;
}

const categorySchema = yup.object().shape({
    name: yup.string().required('El nombre es requerido').max(100, 'No puede tener más de 100 caracteres'),
    description: yup.string(),
});

const RoutineCategoryForm: FC<RoutineCategoryFormProps> = ({ isLoading, submit, data }) => {

    const { userCan } = useContext(PrivilegeContext);
    const mode = data ? 'Editar' : 'Crear';

    const initialValues: IRoutineCategoryForm = {
        routineCategoryId: data?.routineCategoryId || '',
        name: data?.name || '',
        description: data?.description || '',
    };

    const formik = useFormik({
        initialValues,
        validationSchema: categorySchema,
        onSubmit: (values) => { submit(values) },
    });

    const verifyClass = (inputFieldID: keyof IRoutineCategoryForm) => { return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '' };

    const showErrors = (inputFieldID: keyof IRoutineCategoryForm) => {
        const error = formik.touched[inputFieldID] && formik.errors[inputFieldID];
        return (error ? <div className="invalid-feedback">{String(error)}</div> : <></>);
    };

    return (
        <>
            <form onSubmit={formik.handleSubmit} autoComplete="off">
                <CardBody isScrollable={false} className="row g-3">
                        <>
                            <FormGroup requiredInputLabel label='Nombre' className='col-md-12'>
                                <Input
                                    id='name' required onChange={formik.handleChange} value={formik.values.name}
                                    onBlur={formik.handleBlur} className={verifyClass('name')}
                                />
                                {showErrors('name')}
                            </FormGroup>

                            <FormGroup label='Descripción' className='col-md-12'>
                                <textarea
                                    id='description' onChange={formik.handleChange} value={formik.values.description} rows={3}
                                    onBlur={formik.handleBlur} className={'form-control ' + verifyClass('description')}
                                />
                                {showErrors('description')}
                            </FormGroup>
                        </>

                </CardBody>

                <CardFooter className="d-flex justify-content-center">
                    <Button type="submit" size='lg' color='primary'>
                        {isLoading ? <Spinner isSmall /> : mode}
                    </Button>
                </CardFooter>
            </form>
        </>
    )
}

export default RoutineCategoryForm;