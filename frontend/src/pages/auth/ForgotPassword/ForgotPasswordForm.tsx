import React from "react";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import Input from "../../../components/bootstrap/forms/Input";
import Button from "../../../components/bootstrap/Button";
import Spinner from "../../../components/bootstrap/Spinner";
import { useFormik } from 'formik';

interface LoginForgotPasswordFormProps {
    isLoading: boolean;
    submit: Function;
    errorsBool: boolean;
}

export const LoginForgotPasswordForm: React.FC<LoginForgotPasswordFormProps> = ({ isLoading = false, submit, errorsBool }) => {

    const formik = useFormik({
        enableReinitialize: true,
        initialValues: {
            forgotUsername: ''
        },
        validate: (values) => {
            const errors: { forgotUsername?: string; loginPassword?: string } = {};

            if (!values.forgotUsername) {
                errors.forgotUsername = 'Campo obligatorio';
            }
            return errors;
        },
        validateOnChange: false,
        onSubmit: (values) => {
            submit(values.forgotUsername);
        },
    });

    return (
        <form onSubmit={formik.handleSubmit} className='row g-4'>
            <div className='col-12'>
                <FormGroup id='forgotUsername' isFloating label='Correo electrónico'>
                    <Input
                        autoComplete='username'
                        value={formik.values.forgotUsername}
                        isTouched={formik.touched.forgotUsername}
                        invalidFeedback={
                            formik.errors.forgotUsername
                        }
                        isValid={formik.isValid}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        onFocus={() => {
                            formik.setErrors({});
                        }}
                    />
                </FormGroup>
            </div>
            <div className='col-12'>
                <Button color='warning' className='w-100 py-3' type='submit' isDisable={isLoading}>
                    {isLoading ? <Spinner /> : 'Enviar email de recuperación'}
                </Button>
            </div>
        </form>
    )
}