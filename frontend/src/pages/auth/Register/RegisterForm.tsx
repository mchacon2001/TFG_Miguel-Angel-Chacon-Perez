import React, { Fragment, useState } from "react";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import Input from "../../../components/bootstrap/forms/Input";
import Button from "../../../components/bootstrap/Button";
import Spinner from "../../../components/bootstrap/Spinner";
import { useFormik } from 'formik';
import * as yup from 'yup';
import { RemoveRedEye } from "../../../components/icon/material-icons";
import PasswordHide from "../../../components/icon/material-icons/PasswordHide";

interface RegisterFormProps {
    isLoading: boolean;
    submit: Function;
    errorsBool: boolean;
}

export interface IRegisterForm {
    name?: string;
    email?: string;
    password?: string;
    re_password?: string;
    birthdate?: string;
    sex?: string;
    targetWeight?: number;
    weight?: number;
    height?: number;
    toGainMuscle?: boolean;
    toLoseWeight?: boolean;
    toMaintainWeight?: boolean;
    toImprovePhysicalHealth?: boolean;
    toImproveMentalHealth?: boolean;
    fixShoulder?: boolean;
    fixKnees?: boolean;
    fixBack?: boolean;
    rehab?: boolean;
}

const today = new Date();
const todayStr = today.toISOString().split('T')[0];

const registerSchema = yup.object({
    name: yup.string()
        .min(1, 'Demasido Corto')
        .max(100, 'Demasiado Largo')
        .required('Campo obligatorio')
        .matches(/^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]+$/, 'Solo se permiten caracteres'),
    email: yup.string().email('Correo Invalido').required('Campo obligatorio'),
    password: yup.string().required('Contraseña obligatoria').min(8, 'Contraseña de al menos 8 caracteres').max(30, 'Contraseña menor de 30 caracteres').matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/, 'La contraseña debe contener al menos una letra mayúscula, una minúscula y un número'),
    re_password: yup.string().required('Confirmacion de contraseña obligatoria').oneOf([yup.ref('password'), ''], 'Contraseñas no coinciden'),
    birthdate: yup.string()
        .required('Campo obligatorio')
        .test('is-valid-date', 'Fecha no válida', value => !!value && !isNaN(Date.parse(value)))
        .test('is-after-1900', 'La fecha debe ser posterior al 01/01/1900', value => {
            if (!value) return false;
            const minDate = new Date('1900-01-01');
            return new Date(value) >= minDate;
        })
        .test('is-before-today', 'La fecha debe ser anterior a hoy', value => {
            if (!value) return false;
            const today = new Date();
            today.setHours(23, 59, 59, 999);
            return new Date(value) < today;
        }),
    sex: yup.string().oneOf(['male', 'female'], 'Selecciona sexo').required('Campo obligatorio'),
    targetWeight: yup.number()
        .typeError('Debe ser un número')
        .min(20, 'Debe ser mayor o igual a 20kg')
        .max(500, 'Debe ser menor de 500kg')
        .required('Campo obligatorio'),
    weight: yup.number()
        .typeError('Debe ser un número')
        .min(20, 'Debe ser mayor o igual a 20kg')
        .max(500, 'Debe ser menor de 500kg')
        .required('Campo obligatorio'),
    height: yup.number()
        .typeError('Debe ser un número')
        .min(80, 'Debe ser mayor o igual a 80cm')
        .max(250, 'Debe ser menor de 250cm')
        .required('Campo obligatorio'),
    toGainMuscle: yup.boolean().required('Campo obligatorio'),
    toLoseWeight: yup.boolean().required('Campo obligatorio'),
    toMaintainWeight: yup.boolean().required('Campo obligatorio'),
    toImprovePhysicalHealth: yup.boolean().required('Campo obligatorio'),
    toImproveMentalHealth: yup.boolean().required('Campo obligatorio'),
    fixShoulder: yup.boolean().required('Campo obligatorio'),
    fixKnees: yup.boolean().required('Campo obligatorio'),
    fixBack: yup.boolean().required('Campo obligatorio'),
    rehab: yup.boolean().required('Campo obligatorio'),
});



export const RegisterForm: React.FC<RegisterFormProps> = ({ isLoading = false, submit, errorsBool}) => {

    // Función para validar que solo se permitan números enteros
    const handleNumericInput = (e: React.KeyboardEvent<HTMLInputElement>) => {
        // Permitir teclas de control (backspace, delete, tab, escape, enter, etc.)
        const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
        
        // Si es una tecla de control, permitir
        if (allowedKeys.includes(e.key)) {
            return;
        }
        
        // Si no es un número (0-9), prevenir la entrada
        if (!/^[0-9]$/.test(e.key)) {
            e.preventDefault();
        }
    };

    // Función para manejar el pegado y asegurar que solo contenga números
    const handlePaste = (e: React.ClipboardEvent<HTMLInputElement>) => {
        const pastedText = e.clipboardData.getData('text');
        // Si el texto pegado no son solo números, prevenir el pegado
        if (!/^\d+$/.test(pastedText)) {
            e.preventDefault();
        }
    };

    const formik = useFormik({
        initialValues: {
            name: '',
            email: '',
            password: '',
            re_password: '',
            birthdate: '',
            sex: '',
            targetWeight: '',
            weight: '',
            height: '',
            toGainMuscle: false,
            toLoseWeight: false,
            toMaintainWeight: false,
            toImprovePhysicalHealth: false,
            toImproveMentalHealth: false,
            fixShoulder: false,
            fixKnees: false,
            fixBack: false,
            rehab: false,
        },
        validationSchema: registerSchema,
        onSubmit: (values) => {
            submit(
                values.name,
                values.email,
                values.password,
                values.sex, 
                values.targetWeight,
                values.birthdate,
                values.height,
                values.weight,
                values.toGainMuscle,
                values.toLoseWeight,
                values.toMaintainWeight,
                values.toImprovePhysicalHealth,
                values.toImproveMentalHealth,
                values.fixShoulder,
                values.fixKnees,
                values.fixBack,
                values.rehab
            ); 
        },
    });

    // Handle mutually exclusive weight objectives
    const handleWeightObjectiveChange = (objectiveType: 'toGainMuscle' | 'toLoseWeight' | 'toMaintainWeight') => {
        const newValue = !formik.values[objectiveType];
        
        if (newValue) {
            // If checking this option, uncheck the others
            formik.setFieldValue('toGainMuscle', objectiveType === 'toGainMuscle');
            formik.setFieldValue('toLoseWeight', objectiveType === 'toLoseWeight');
            formik.setFieldValue('toMaintainWeight', objectiveType === 'toMaintainWeight');
        } else {
            // If unchecking, just uncheck this one
            formik.setFieldValue(objectiveType, false);
        }
    };
    
    const verifyClass = (inputFieldID: keyof IRegisterForm) => { return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '' };

    const [showPassword, setShowPassword] = useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);

    return (
        <Fragment>
            <form onSubmit={formik.handleSubmit} className='row g-4'>
                <div className='col-12'>
                    <FormGroup requiredInputLabel className="mb-4" id='name' isFloating label='Nombre'>
                        <Input
                            value={formik.values.name}
                            isTouched={formik.touched.name}
                            invalidFeedback={
                                formik.errors.name
                            }
                            isValid={formik.isValid}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            onFocus={() => {
                                formik.setErrors({});
                            }}
                            className={verifyClass('name')}
                        />
                    </FormGroup>
                    <FormGroup requiredInputLabel className="mb-4" id='email' isFloating label='Correo electrónico'>
                        <Input
                            value={formik.values.email}
                            isTouched={formik.touched.email}
                            invalidFeedback={
                                formik.errors.email
                            }
                            isValid={formik.isValid}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            onFocus={() => {
                                formik.setErrors({});
                            }}
                            className={verifyClass('email')}
                        />
                    </FormGroup>
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
                    <FormGroup label='Fecha de nacimiento' className="mb-4" id='birthdate' isFloating>
                        <Input
                            id="birthdate"
                            type="date"
                            value={formik.values.birthdate || ''}
                            isTouched={formik.touched.birthdate}
                            invalidFeedback={formik.errors.birthdate}
                            isValid={formik.isValid}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                            className={verifyClass('birthdate')}
                            {...({ min: '1900-01-01', max: new Date().toISOString().split('T')[0] } as any)}
                        />

                    </FormGroup>
                    <FormGroup label='Sexo' className="mb-4" id='sex' isFloating>
                        <select
                            id="sex"
                            name="sex"
                            className={`form-select ${verifyClass('sex')}`}
                            value={formik.values.sex || ''}
                            onChange={formik.handleChange}
                            onBlur={formik.handleBlur}
                        >
                            <option value="" disabled hidden>Seleccione sexo</option>
                            <option value="male">Masculino</option>
                            <option value="female">Femenino</option>
                        </select>

                    </FormGroup>
                    <FormGroup label='Peso objetivo (kg)' className="mb-4" id='targetWeight' isFloating>
                        <Input
                            id="targetWeight"
                            type="number"
                            min={20}
                            max={500}
                            step={1}
                            inputMode="numeric"
                            value={formik.values.targetWeight || ''}
                            isTouched={formik.touched.targetWeight}
                            invalidFeedback={formik.errors.targetWeight}
                            isValid={formik.isValid}
                            onChange={(e: any) => {
                                const val = e.target.value.replace(/[^0-9]/g, '');
                                formik.setFieldValue('targetWeight', val);
                            }}
                            onBlur={formik.handleBlur}
                            className={verifyClass('targetWeight')}
                            onKeyDown={handleNumericInput}
                            onPaste={handlePaste}
                        />
                    </FormGroup>
                    <FormGroup label='Peso actual (kg)' className="mb-4" id='weight' isFloating>
                        <Input
                            id="weight"
                            type="number"
                            min={20}
                            max={500}
                            step={1}
                            inputMode="numeric"
                            value={formik.values.weight || ''}
                            isTouched={formik.touched.weight}
                            invalidFeedback={formik.errors.weight}
                            isValid={formik.isValid}
                            onChange={(e: any) => {
                                const val = e.target.value.replace(/[^0-9]/g, '');
                                formik.setFieldValue('weight', val);
                            }}
                            onBlur={formik.handleBlur}
                            className={verifyClass('weight')}
                            onKeyDown={handleNumericInput}
                            onPaste={handlePaste}
                        />
                    </FormGroup>
                    <FormGroup label='Altura actual (cm)' className="mb-4" id='height' isFloating>
                        <Input
                            id="height"
                            type="number"
                            min={80}
                            max={250}
                            step={1}
                            inputMode="numeric"
                            value={formik.values.height || ''}
                            isTouched={formik.touched.height}
                            invalidFeedback={formik.errors.height}
                            isValid={formik.isValid}
                            onChange={(e: any) => {
                                const val = e.target.value.replace(/[^0-9]/g, '');
                                formik.setFieldValue('height', val);
                            }}
                            onBlur={formik.handleBlur}
                            className={verifyClass('height')}
                            onKeyDown={handleNumericInput}
                            onPaste={handlePaste}
                        />
                    </FormGroup>

                    {/* Objetivos */}
                    <div className="mb-4">
                        <label className="form-label fw-bold">¿Cuál es tu objetivo?</label>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="toGainMuscle"
                                checked={formik.values.toGainMuscle}
                                onChange={() => handleWeightObjectiveChange('toGainMuscle')}
                            />
                            <label className="form-check-label" htmlFor="toGainMuscle">Ganar músculo</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="toMaintainWeight"
                                checked={formik.values.toMaintainWeight}
                                onChange={() => handleWeightObjectiveChange('toMaintainWeight')}
                            />
                            <label className="form-check-label" htmlFor="toMaintainWeight">Mantener peso</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="toLoseWeight"
                                checked={formik.values.toLoseWeight}
                                onChange={() => handleWeightObjectiveChange('toLoseWeight')}
                            />
                            <label className="form-check-label" htmlFor="toLoseWeight">Perder peso</label>
                        </div>
                    </div>

                    {/* Orientación específica */}
                    <div className="mb-4">
                        <label className="form-label fw-bold">¿Quieres orientar tu ejercicio en algo en concreto?</label>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="toImprovePhysicalHealth"
                                checked={formik.values.toImprovePhysicalHealth}
                                onChange={formik.handleChange}
                            />
                            <label className="form-check-label" htmlFor="toImprovePhysicalHealth">Mejorar salud física</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="toImproveMentalHealth"
                                checked={formik.values.toImproveMentalHealth}
                                onChange={formik.handleChange}
                            />
                            <label className="form-check-label" htmlFor="toImproveMentalHealth">Mejorar salud mental</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="fixShoulder"
                                checked={formik.values.fixShoulder}
                                onChange={formik.handleChange}
                            />
                            <label className="form-check-label" htmlFor="fixShoulder">Corregir hombros</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="fixKnees"
                                checked={formik.values.fixKnees}
                                onChange={formik.handleChange}
                            />
                            <label className="form-check-label" htmlFor="fixKnees">Corregir rodillas</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="fixBack"
                                checked={formik.values.fixBack}
                                onChange={formik.handleChange}
                            />
                            <label className="form-check-label" htmlFor="fixBack">Corregir espalda</label>
                        </div>
                        <div className="form-check form-switch">
                            <input
                                className="form-check-input"
                                type="checkbox"
                                id="rehab"
                                checked={formik.values.rehab}
                                onChange={formik.handleChange}
                            />
                            <label className="form-check-label" htmlFor="rehab">Rehabilitación</label>
                        </div>
                    </div>
                </div>
                <div className='col-12'>
                    <Button color='warning' className='w-100 py-3' type='submit' isDisable={isLoading}>
                        {isLoading ? <Spinner /> : 'Registrarse'}
                    </Button>
                </div>
                
            </form>
        </Fragment>
    )
}

export default RegisterForm;