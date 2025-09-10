import Button from "../../components/bootstrap/Button";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Spinner from "../../components/bootstrap/Spinner";
import { useFormik } from "formik";
import { FC, Fragment, useContext, useEffect, useState } from "react";
import { CardBody, CardFooter, CardTitle } from "../../components/bootstrap/Card";
import Select from "../../components/bootstrap/forms/Select";
import * as yup from "yup";

interface CreateFormProps {
    isLoading: boolean;
    submit: Function;
    entityData?: any;
}

export interface IFoodForm {
    id?: string;
    name: string;
    description?: string;
    calories: number;
    proteins: number;
    carbs: number;
    fats: number;
}

const FoodSchema = yup.object({
    name: yup.string().min(1, 'Demasiado corto').max(100, 'Demasiado largo').required('Campo obligatorio'),
    description: yup.string().max(1000, 'Máximo 1000 caracteres'),
});

const FoodForm: FC<CreateFormProps> = ({ isLoading, submit, entityData }) => {

    const mode = entityData ? 'Editar' : 'Crear';
    const entityInitialValues: IFoodForm = {
        name: entityData?.name || '',
        description: entityData?.description || '',
        calories: entityData?.calories || 0,
        proteins: entityData?.proteins || 0,
        carbs: entityData?.carbs || 0,
        fats: entityData?.fats || 0,
    };

const formik = useFormik({
    initialValues: entityInitialValues,
    validationSchema: FoodSchema,
    onSubmit: values => {
        submit({ ...values });
    },
    enableReinitialize: true,
});


    const verifyClass = (inputFieldID: keyof IFoodForm) => { return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '' };

    const showErrors = (inputFieldID: keyof IFoodForm) => {
        const error = formik.touched[inputFieldID] && formik.errors[inputFieldID];
        return (error ? <div className="invalid-feedback">{String(error)}</div> : <></>);
    };

    return (
        <Fragment>
            <form onSubmit={formik.handleSubmit} autoComplete="off">
                <CardBody className='row g-3 p-4'>
                    <CardTitle>Información general</CardTitle>
                    <>
                        <FormGroup requiredInputLabel label="Nombre" className="col-md-4">
                            <Input
                                id="name"
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                value={formik.values.name}
                                className={verifyClass("name")}
                            />
                            {showErrors("name")}
                        </FormGroup>
                        <FormGroup label="Calorías (kcal)" className="col-md-2">
                            <Input
                                id="calories"
                                type="text"
                                inputMode="numeric"
                                onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                    const value = e.target.value.replace(/[^0-9]/g, '');
                                    formik.setFieldValue('calories', value);
                                }}
                                onBlur={formik.handleBlur}
                                value={formik.values.calories}
                                className={verifyClass("calories")}
                            />
                            {showErrors("calories")}
                        </FormGroup>
                        <FormGroup label="Proteínas (g)" className="col-md-2">
                            <Input
                                id="proteins"
                                type="text"
                                inputMode="numeric"
                                onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                    const value = e.target.value.replace(/[^0-9]/g, '');
                                    formik.setFieldValue('proteins', value);
                                }}
                                onBlur={formik.handleBlur}
                                value={formik.values.proteins}
                                className={verifyClass("proteins")}
                            />
                            {showErrors("proteins")}
                        </FormGroup>
                        <FormGroup label="Carbohidratos (g)" className="col-md-2">
                            <Input
                                id="carbs"
                                type="text"
                                inputMode="numeric"
                                onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                    const value = e.target.value.replace(/[^0-9]/g, '');
                                    formik.setFieldValue('carbs', value);
                                }}
                                onBlur={formik.handleBlur}
                                value={formik.values.carbs}
                                className={verifyClass("carbs")}
                            />
                            {showErrors("carbs")}
                        </FormGroup>
                        <FormGroup label="Grasas (g)" className="col-md-2">
                            <Input
                                id="fats"
                                type="text"
                                inputMode="numeric"
                                onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                    const value = e.target.value.replace(/[^0-9]/g, '');
                                    formik.setFieldValue('fats', value);
                                }}
                                onBlur={formik.handleBlur}
                                value={formik.values.fats}
                                className={verifyClass("fats")}
                            />
                            {showErrors("fats")}
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
                        {isLoading ? <Spinner isSmall /> : `${mode} Alimento`}
                    </Button>
                </CardFooter>
            </form>
        </Fragment>
    );
}

export default FoodForm;
