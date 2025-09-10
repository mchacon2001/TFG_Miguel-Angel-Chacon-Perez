import React, { useState } from "react";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import Input from "../../../components/bootstrap/forms/Input";
import Button from "../../../components/bootstrap/Button";
import Spinner from "../../../components/bootstrap/Spinner";
import { useFormik } from 'formik';
import * as yup from "yup";
import { useLocation } from "react-router-dom";
import {toast} from "react-toastify";
import { RemoveRedEye } from "../../../components/icon/material-icons";
import PasswordHide from "../../../components/icon/material-icons/PasswordHide";

interface LoginResetPasswordFormProps {
    isLoading: boolean;
    submit: Function;
    errorsBool: boolean;
}

const ResetPasswordSchema = yup.object({
    password: yup.string().required('Campo obligatorio').min(8, 'Contraseña de al menos 8 caracteres').max(30, 'Contraseña menor de 30 caracteres').matches(
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/,
        'La contraseña debe contener al menos una letra mayúscula, una minúscula y un número'
      ),
    re_password: yup.string().required('Campo obligatorio').oneOf([yup.ref('password'), ''], 'Contraseñas no coinciden'),
});

export const LoginResetPasswordForm: React.FC<LoginResetPasswordFormProps> = ({isLoading = false, submit, errorsBool}) => {
    
    const userToken = new URLSearchParams(useLocation().search).get('token');
    const [showPassword, setShowPassword] = useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);

    const formik = useFormik({
		enableReinitialize: true,
		initialValues: {
			password: '',
            re_password: ''
		},
        validationSchema: ResetPasswordSchema,
		validateOnChange: false,
		onSubmit: (values) => {
            if(userToken && userToken.length > 10) {
                submit(userToken, values.password, values.re_password );
            } else {
                toast.error('Token invalido o nulo.');
            }
		},
	});

    const verifyClass = (inputFieldID: keyof typeof formik.values) => { return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '' };

    return(
        <form onSubmit={formik.handleSubmit} className='row g-4'>
        <div className='col-12'>
                    <div className="d-flex flex-row flex-nowrap mt-3 gap-2">
                        <div className="col-10">
                            <FormGroup requiredInputLabel className="mb-4" id="password" isFloating label="Contraseña">
                                <Input
                                    type={showPassword ? 'text' : 'password'}
                                    value={formik.values.password}
                                    isTouched={formik.touched.password}
                                    invalidFeedback={formik.errors.password}
                                    isValid={formik.isValid}
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    onFocus={() => formik.setErrors({})}
                                    className={verifyClass('password')}
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

                    <div className="d-flex flex-row flex-nowrap gap-2">
                        <div className="col-10">
                            <FormGroup requiredInputLabel className="mb-4" id="re_password" isFloating label="Confirmar contraseña">
                                <Input
                                    id="re_password"
                                    type={showPasswordConfirmation ? 'text' : 'password'}
                                    value={formik.values.re_password}
                                    isTouched={formik.touched.re_password}
                                    invalidFeedback={formik.errors.re_password}
                                    isValid={formik.isValid}
                                    onChange={formik.handleChange}
                                    onBlur={formik.handleBlur}
                                    onFocus={() => formik.setErrors({})}
                                    className={verifyClass('re_password')}
                                />
                            </FormGroup>
                        </div>
                        <div className="col-2 d-flex justify-content-center h-50">
                            <Button
                                isOutline
                                color="primary"
                                onClick={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                                className="p-3">
                                {!showPasswordConfirmation ? (
                                    <RemoveRedEye fontSize="1.5em" />
                                ) : (
                                    <PasswordHide fontSize="1.5em" />
                                )}
                            </Button>
                        </div>
                    </div>

                </div>
        <div className='col-12'>
            <Button color='warning' className='w-100 py-3' type='submit'  isDisable={isLoading}>
                {isLoading ? <Spinner/> : 'Resetar contraseña'}
            </Button>
        </div>
    </form>
    ) 
}