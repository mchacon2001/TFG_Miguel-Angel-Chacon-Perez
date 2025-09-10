import Button from "../../components/bootstrap/Button";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Spinner from "../../components/bootstrap/Spinner";
import { useFormik } from "formik";
import { FC, Fragment, useContext, useEffect, useState } from "react";
import { CardBody, CardFooter, CardTitle } from "../../components/bootstrap/Card";
import useExercisesCategories from "../../hooks/useExercisesCategories";
import Select from "../../components/bootstrap/forms/Select";
import * as yup from "yup";

interface CreateFormProps {
    isLoading: boolean;
    submit: Function;
    entityData?: any;
}

export interface IExerciseForm {
    id?: string;
    name?: string;
    exerciseCategoryId?: string;
    description?: string;
}

const ExerciseSchema = yup.object({
    name: yup.string().min(1, 'Demasiado corto').max(100, 'Demasiado largo').required('Campo obligatorio'),
    description: yup.string(),
});

const ExerciseForm: FC<CreateFormProps> = ({ isLoading, submit, entityData }) => {
    const { fetchExercisesCategories, getExercisesCategoriesList } = useExercisesCategories();

    const mode = entityData ? 'Editar' : 'Crear';
    const entityInitialValues: IExerciseForm = {
        exerciseCategoryId: entityData?.exerciseCategories?.id || '',
        name: entityData?.name || '',
        description: entityData?.description || '',
    };


    const formik = useFormik({
        initialValues: entityInitialValues,
        validationSchema: ExerciseSchema,
        onSubmit: values => {
            submit({
                ...values,
                description: values.description ?? '',
            });
        },
        enableReinitialize: true,
    });

    const verifyClass = (inputFieldID: keyof IExerciseForm) => { return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '' };

    const showErrors = (inputFieldID: keyof IExerciseForm) => {
        const error = formik.touched[inputFieldID] && formik.errors[inputFieldID];
        return (error ? <div className="invalid-feedback">{String(error)}</div> : <></>);
    };

    return (
        <Fragment>
            <form onSubmit={formik.handleSubmit} autoComplete="off">
                <CardBody className='row g-3 p-4'>
                    <CardTitle>Información general</CardTitle>
                    <>
                        <FormGroup requiredInputLabel label="Nombre" className="col-md-6">
                            <Input
                                id="name"
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                value={formik.values.name}
                                className={verifyClass("name")}
                            />
                            {showErrors("name")}
                        </FormGroup>
                        <FormGroup requiredInputLabel label='Categoría' className='col-md-6'>
                            <Select
                                id='exerciseCategoryId' required onChange={formik.handleChange}
                                value={formik.values.exerciseCategoryId}
                                list={getExercisesCategoriesList()}
                                className={verifyClass('exerciseCategoryId')}
                                placeholder='Elegir categoría ...' ariaLabel='Elegir categoría ...'
                            />
                            {showErrors('exerciseCategoryId')}
                        </FormGroup>
                        <FormGroup label="Descripción" className="col-md-12">
                            <textarea
                                id="description"
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                value={formik.values.description}
                                rows={3}
                                className={`form-control ${verifyClass("description")}`}
                            />
                            {showErrors("description")}
                        </FormGroup>
                    </>
                </CardBody>
                <CardFooter className="d-flex justify-content-center">
                    <Button type="submit" size='lg' color='primary'>
                        {isLoading ? <Spinner isSmall /> : `${mode} Ejercicio`}
                    </Button>
                </CardFooter>
            </form>
        </Fragment>
    );
}

export default ExerciseForm;
