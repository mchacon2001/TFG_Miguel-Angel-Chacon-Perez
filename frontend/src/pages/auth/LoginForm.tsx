import React, { useState } from "react";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Button from "../../components/bootstrap/Button";
import { useFormik } from 'formik';
import Spinner from "../../components/bootstrap/Spinner";
import { Link, useNavigate } from "react-router-dom";
import { RemoveRedEye } from "../../components/icon/material-icons";
import PasswordHide from "../../components/icon/material-icons/PasswordHide";

interface LoginFormProps {
    isLoading: boolean;
    submit: Function;
    errorsBool: boolean;
}

export const LoginForm: React.FC<LoginFormProps> = ({ isLoading = false, submit, errorsBool }) => {

    const [showPassword, setShowPassword] = useState(false);
    const navigate = useNavigate();

    //-----------------------------------
    /**
     *  EN: Form used to login users validation
     *  ES: Formulario usado para el login de usuarios con validación
     */
    //-----------------------------------
    const formik = useFormik({
        enableReinitialize: true,
        initialValues: {
            loginUsername: '',
            loginPassword: '',
        },
        validate: (values) => {
            const errors: { loginUsername?: string; loginPassword?: string } = {};

            if (!values.loginUsername) {
                errors.loginUsername = 'Campo obligatorio';
            }

            if (!values.loginPassword) {
                errors.loginPassword = 'Campo obligatorio';
            }

            return errors;
        },
        validateOnChange: false,
        onSubmit: (values) => {
            submit(values.loginUsername, values.loginPassword);
        },
    });

    const verifyClass = (inputFieldID: keyof typeof formik.values) => {
        return formik.touched[inputFieldID] && formik.errors[inputFieldID] ? 'is-invalid' : '';
    };
    
    return (
        <form onSubmit={formik.handleSubmit} className='row g-4'>
            <div className='col-12'>
                <FormGroup id='loginUsername' isFloating requiredInputLabel label='Correo electrónico'>
                    <Input
                        autoComplete='username'
                        value={formik.values.loginUsername}
                        isTouched={formik.touched.loginUsername}
                        invalidFeedback={formik.errors.loginUsername}
                        isValid={formik.isValid}
                        onChange={formik.handleChange}
                        onBlur={formik.handleBlur}
                        onFocus={() => {
                            formik.setErrors({});
                        }}
                        className={verifyClass('loginUsername')}
                    />
                </FormGroup>
                <div className="d-flex flex-row flex-nowrap mt-3 gap-2">
                        <div className="col-10">
                            <FormGroup requiredInputLabel id="loginPassword" isFloating label="Contraseña">
                                <Input
                                    type={showPassword ? 'text' : 'password'}
                                    value={formik.values.loginPassword}
                                    isTouched={formik.touched.loginPassword}
                                    invalidFeedback={formik.errors.loginPassword}
                                    isValid={formik.isValid}
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    onFocus={() => formik.setErrors({})}
                                    className={verifyClass('loginPassword')}
                                />
                            </FormGroup>
                        </div>
                        <div className="col-2 d-flex justify-content-center h-50">
                            <Button
                                isOutline
                                color="primary"
                                onClick={() => setShowPassword(!showPassword)}
                                className="p-3">
                                {!showPassword ? (
                                    <RemoveRedEye fontSize="1.5em" />
                                ) : (
                                    <PasswordHide fontSize="1.5em" />
                                )}
                            </Button>
                        </div>
                    </div>
            </div>
            <div className='col-12 text-center'>
                <Link to='/forgot-password' className='text-primary fw-bold'>
                     ¿Has olvidado tu contraseña?
                </Link>
            </div>

            <div className='col-12'>
                <Button color='warning' className='w-100 py-3' type='submit' isDisable={isLoading}>
                    {isLoading ? <Spinner /> : 'Iniciar Sesión'}
                </Button>
            </div>
    
            <div className='col-12 text-center'>
                <hr className='my-4' />
                <span className="d-block text-muted">¿Eres nuevo en <strong>BrainyGym</strong>?</span>
            </div>
    
            <div className='col-12'>
                <Link to='/register'>
                    <Button color='primary' className='w-100 py-3' type='button' onClick={() => {navigate("create") }}>
                        Crear Cuenta
                    </Button>
                </Link>
            </div>
        </form>
    );
}