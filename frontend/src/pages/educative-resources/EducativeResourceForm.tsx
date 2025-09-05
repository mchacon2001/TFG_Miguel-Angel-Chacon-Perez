import Button from "../../components/bootstrap/Button";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Spinner from "../../components/bootstrap/Spinner";
import { useFormik } from "formik";
import { FC, Fragment } from "react";
import { CardBody, CardFooter, CardTitle } from "../../components/bootstrap/Card";
import * as yup from "yup";

interface CreateFormProps {
    isLoading: boolean;
    submit: Function;
    entityData?: any;
}

export interface IEducativeResourceForm {
    id?: string;
    title?: string;
    youtubeUrl?: string;
    description?: string;
    isVideo?: boolean;
    tag?: string;
}

export const TYPES_OF_EDUCATIVE_RESOURCES = [
    {label: 'Entrenamientos', value: 'training'},
    {label: 'Salud', value: 'health'},
    {label: 'Nutrición', value: 'nutrition'},
    {label: 'Artículos científicos', value: 'scientific_articles'},
    {label: 'Otro', value: 'other'},
];

const EducativeResourceSchema = yup.object({
    title: yup.string().min(1, 'Demasiado corto').max(100, 'Demasiado largo').required('Campo obligatorio'),
    youtubeUrl: yup.string().url('URL inválida').required('Campo obligatorio'),
    description: yup.string().max(1000, 'Máximo 1000 caracteres'),
    isVideo: yup.boolean().required('Campo obligatorio'),
    tag: yup.string().min(1, 'Demasiado corto').max(100, 'Demasiado largo').required('Campo obligatorio'),
});

const EducativeResourceForm: FC<CreateFormProps> = ({ isLoading, submit, entityData }) => {
    const mode = entityData ? 'Editar' : 'Crear';
    const entityInitialValues: IEducativeResourceForm = {
        title: entityData?.title || '',
        youtubeUrl: entityData?.youtubeUrl || '',
        description: entityData?.description || '',
        isVideo: entityData?.isVideo || false,
        tag: entityData?.tag || '',
    };

    const formik = useFormik({
        initialValues: entityInitialValues,
        validationSchema: EducativeResourceSchema,
        onSubmit: values => {
            submit({
                ...values,
                description: values.description ?? '',
                isVideo: values.isVideo ?? false,
                tag: values.tag ?? '',
            });
        },
        enableReinitialize: true,
    });

    const verifyClass = (inputFieldID: keyof IEducativeResourceForm) => (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '';

    const showErrors = (inputFieldID: keyof IEducativeResourceForm) => {
        const error = formik.touched[inputFieldID] && formik.errors[inputFieldID];
        return (error ? <div className="invalid-feedback">{String(error)}</div> : <></>);
    };

    return (
        <Fragment>
            <form onSubmit={formik.handleSubmit} autoComplete="off">
                <CardBody className='row g-3 p-4'>
                    <CardTitle>Información del recurso educativo</CardTitle>
                    <>
                        <FormGroup requiredInputLabel label="Título del recurso" className="col-md-4">
                            <Input
                                id="title"
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                value={formik.values.title}
                                className={verifyClass("title")}
                            />
                            {showErrors("title")}
                        </FormGroup>
                        <FormGroup requiredInputLabel label="URL" className="col-md-4">
                            <Input
                                id="youtubeUrl"
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                value={formik.values.youtubeUrl}
                                className={verifyClass("youtubeUrl")}
                                placeholder="https://www.url.com/url"
                            />
                            {showErrors("youtubeUrl")}
                        </FormGroup>
                        <FormGroup requiredInputLabel label="Tipo de recurso" className="col-md-4">
                            <select
                                id="tag"
                                onChange={formik.handleChange}
                                onBlur={formik.handleBlur}
                                value={formik.values.tag}
                                className={`form-select ${verifyClass("tag")}`}
                            >
                                <option value="">Seleccione un tipo</option>
                                {TYPES_OF_EDUCATIVE_RESOURCES.map((type) => (
                                    <option key={type.value} value={type.value}>
                                        {type.label}
                                    </option>
                                ))}
                            </select>
                            {showErrors("tag")}
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

                        <FormGroup requiredInputLabel className="col-md-4">
                            <div className="d-flex align-items-center">
                                <label htmlFor="isVideo" className="form-label mb-0 me-3">¿Es un video?</label>
                                <div className="form-check form-switch m-0">
                                    <input
                                        type="checkbox"
                                        id="isVideo"
                                        onChange={formik.handleChange}
                                        onBlur={formik.handleBlur}
                                        checked={formik.values.isVideo}
                                        className={`form-check-input ${verifyClass("isVideo")}`}
                                        role="switch"
                                    />
                                </div>
                            </div>
                            {showErrors("isVideo")}
                        </FormGroup>
                    </>
                </CardBody>
                <CardFooter className="d-flex justify-content-center">
                    <Button type="submit" size='lg' color='primary'>
                        {isLoading ? <Spinner isSmall /> : `${mode} Recurso`}
                    </Button>
                </CardFooter>
            </form>
        </Fragment>
    );
}

export default EducativeResourceForm;
