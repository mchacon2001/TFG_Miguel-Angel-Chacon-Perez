import Button from "../../components/bootstrap/Button";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Select from "../../components/bootstrap/forms/Select";
import Spinner from "../../components/bootstrap/Spinner";
import AsyncImg from "../../components/AsyncImg";
import { useFormik } from "formik";
import { FC, Fragment, useCallback, useEffect, useRef, useState } from "react";
import { CardBody, CardFooter, CardTitle } from "../../components/bootstrap/Card";
import { UserService } from "../../services/users/userService";
import { toast } from "react-toastify";
import { Schema } from "yup";
import { userIsSuperAdmin } from "../../utils/userIsSuperAdmin";
import useFetch from "../../hooks/useFetch";
import useFilters from "../../hooks/useFilters";
import * as yup from "yup";
import 'react-phone-input-2/lib/style.css'
import { userIsAdmin } from "../../utils/userIsAdmin";

interface CreateFormProps {
    isLoading: boolean;
    submit: Function;
    userData?: any;
}

export interface IUserForm {
    id?: string;
    name?: string;
    email?: string;
    roleId?: string;
    password?: string | null;
    re_password?: string | null;
    active?: boolean;
    birthdate?: string;
    sex?: string;
    targetWeight?: number;
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

// Añadir los campos al esquema de validación
const UserEditSchema = yup.object({
    name: yup.string().min(1, 'Demasido Corto').max(100, 'Demasiado Largo').required('Campo Obligatorio'),
    email: yup.string().email('Correo Invalido').required('Campo Obligatorio'),
    roleId: yup.string().required('Debes elegir un rol de la organización para poder editar un usuario'),
    password: yup.string().min(8, 'Contraseña de al menos 8 caracteres').max(30, 'Contraseña menor de 30 caracteres').matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/, 'La contraseña debe contener al menos una letra mayúscula, una minúscula y un número').nullable(),
    re_password: yup.string().when('password', {
        is: (val: string | null | undefined) => val !== null && val !== undefined && val.length > 0,
        then: (schema: Schema) => schema.required('Confirmacion de contraseña obligatoria').oneOf([yup.ref('password'), ''], 'Contraseñas no coinciden'),
        otherwise: (schema: Schema) => schema.nullable(),
    }).nullable(),
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
    sex: yup.string().oneOf(['male', 'female', 'other'], 'Valor no válido').required('Campo obligatorio'),
    targetWeight: yup.number().min(20, 'Peso mínimo 20kg').max(500, 'Peso máximo 500kg').required('Campo obligatorio'),
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

const UserCreateSchema = UserEditSchema.concat(
    yup.object({
        roleId: yup.string().required('Debes elegir un rol para poder crear un usuario'),
        password: yup.string().required('Contraseña Obligatoria').min(8, 'Contraseña de al menos 8 caracteres').max(30, 'Contraseña menor de 30 caracteres').matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/, 'La contraseña debe contener al menos una letra mayúscula, una minúscula y un número'),
        re_password: yup.string().required('Confirmacion de contraseña obligatoria').oneOf([yup.ref('password'), ''], 'Contraseñas no coinciden'),
    })
);

const UserForm: FC<CreateFormProps> = ({ isLoading, submit, userData }) => {

    const { filters, updateFilters } = useFilters();
    const mode = userData ? 'Editar' : 'Crear';

    const userInitialValues: any = {
        name: userData?.name,
        email: userData?.email,
        active: userData?.active || true,
        roleId: userData?.userRoles[0]?.role?.id,
        password: null,
        re_password: null,
        birthdate: userData?.birthdate
            ? (typeof userData.birthdate === 'object' && userData.birthdate.date
                ? userData.birthdate.date.substring(0, 10)
                : userData.birthdate)
            : '',
        sex: userData?.sex || '',
        targetWeight: userData?.targetWeight || '',
        toGainMuscle: userData?.toGainMuscle ?? false,
        toLoseWeight: userData?.toLoseWeight ?? false,
        toMaintainWeight: userData?.toMaintainWeight ?? false,
        toImprovePhysicalHealth: userData?.toImprovePhysicalHealth ?? false,
        toImproveMentalHealth: userData?.toImproveMentalHealth ?? false,
        fixShoulder: userData?.fixShoulder ?? false,
        fixKnees: userData?.fixKnees ?? false,
        fixBack: userData?.fixBack ?? false,
        rehab: userData?.rehab ?? false,
    };

    const formik = useFormik({
        initialValues: userInitialValues,
        validationSchema: (mode === 'Editar') ? UserEditSchema : UserCreateSchema,
        onSubmit: values => {
            values = {
                ...userData,
                ...values
            }
            delete values.userPermissions;
            delete values.userRoles;
            submit(values)
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

    const [roleList] = useFetch(useCallback(async () => {
        const userService = new UserService();
        const response = await userService.getUserRoles(filters);
        return response.getResponseData();
    }, [filters]));

    const getRolesList = () => {
        if (roleList) {
            return roleList.roles
                .filter((role: any) => role?.id !== 1)
                .map((role: any) => ({
                    value: role.id,
                    label: role.name
                }));
        }
        return [];
    };


    const verifyClass = (inputFieldID: keyof IUserForm) => { return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '' };

    const showErrors = (inputFieldID: keyof IUserForm) => {
        // @ts-ignore
        return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? <div className="invalid-feedback">{formik.errors[inputFieldID]}</div> : <></>;
    };

    // Determina si el usuario es admin o super admin
    const isAdminOrSuperAdmin = userIsAdmin() || userIsSuperAdmin();

    return (
        <Fragment>
            <form onSubmit={formik.handleSubmit} autoComplete="off">
                <CardBody isScrollable={false}>
                    <CardTitle>Información general</CardTitle>
                    <div className="row g-3 mt-3">
                        <FormGroup requiredInputLabel label='Nombre' className='col-md-6'>
                            <Input
                            id='name'
                            onChange={formik.handleChange} 
                            value={formik.values.name || ''}
                            onBlur={formik.handleBlur} className={verifyClass('name')} />
                            {showErrors('name')}
                        </FormGroup>
                        <FormGroup requiredInputLabel label='Email' className='col-md-6'>
                            <Input id='email'  type='email' onChange={formik.handleChange}
                                value={formik.values.email || ''} onBlur={formik.handleBlur}
                                className={verifyClass('email')} />
                            {showErrors('email')}
                        </FormGroup>
                        {/* Solo mostrar el selector de rol si es admin o super admin */}
                        {isAdminOrSuperAdmin && (
                            <FormGroup id='roleId' requiredInputLabel label='Rol' className='col-md-6'>
                                <Select id='roleId' className={verifyClass('roleId')} ariaLabel='Default select example'
                                    placeholder='Elegir rol...'
                                    onChange={formik.handleChange} value={formik.values.roleId ? formik.values.roleId.toString() : ''}
                                    list={getRolesList()}
                                />
                                {showErrors('roleId')}
                            </FormGroup>
                        )}
                        <FormGroup requiredInputLabel label='Fecha de nacimiento' className='col-md-6'>
                            <Input
                                id='birthdate'
                                type='date'
                                onChange={formik.handleChange}
                                value={formik.values.birthdate || ''}
                                onBlur={formik.handleBlur}
                                className={verifyClass('birthdate')}
                                {...({ min: '1900-01-01', max: new Date().toISOString().split('T')[0] } as any)}
                            />
                            {showErrors('birthdate')}
                        </FormGroup>

                        <FormGroup requiredInputLabel label='Sexo' className='col-md-6'>
                            <Select
                                id='sex'
                                className={verifyClass('sex')}
                                ariaLabel='Selecciona sexo'
                                placeholder='Selecciona sexo...'
                                onChange={formik.handleChange}
                                value={formik.values.sex || ''}
                                list={[
                                    { value: 'male', label: 'Hombre' },
                                    { value: 'female', label: 'Mujer' },
                                    { value: 'other', label: 'Otro' }
                                ]}
                            />
                            {showErrors('sex')}
                        </FormGroup>
                        <FormGroup requiredInputLabel label='Peso objetivo (kg)' className='col-md-6'>
                            <Input
                                id='targetWeight'
                                type='text'
                                inputMode='numeric'
                                min={20}
                                max={500}
                                onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                    const value = e.target.value.replace(/[^0-9]/g, '');
                                    formik.setFieldValue('targetWeight', value);
                                }}
                                value={formik.values.targetWeight || ''}
                                onBlur={formik.handleBlur}
                                className={verifyClass('targetWeight')}
                            />
                            {showErrors('targetWeight')}
                        </FormGroup>
                    </div>

                    <div className="row g-3 mt-2">
                        <FormGroup label='Contraseña' className='col-md-6' requiredInputLabel={mode === 'Crear' ? true : false} >
                            <Input id='password' type={'password'} autoComplete="new-password" onChange={formik.handleChange}
                                value={formik.values.password || ''} onBlur={formik.handleBlur}
                                className={verifyClass('password')} />
                            {showErrors('password')}
                        </FormGroup>

                        <FormGroup label='Confirmar Contraseña' className='col-md-6' requiredInputLabel={mode === 'Crear' ? true : false} >
                            <Input id='re_password' type={'password'} autoComplete="new-password" onChange={formik.handleChange}
                                value={formik.values.re_password || ''} onBlur={formik.handleBlur}
                                className={verifyClass('re_password')} />
                            {showErrors('re_password')}
                        </FormGroup>
                    </div>

                    {/* Objetivos y orientación específica en la misma fila */}
                    <div className="row mb-4">
                        <div className="col-md-6 mt-2">
                            <label className="form-label fw-bold">Objetivo físico</label>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="toImprovePhysicalHealth"
                                    checked={formik.values.toImprovePhysicalHealth || false}
                                    onChange={formik.handleChange}
                                />
                                <label className="form-check-label" htmlFor="toImprovePhysicalHealth">Mejorar salud física</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="toImproveMentalHealth"
                                    checked={formik.values.toImproveMentalHealth || false}
                                    onChange={formik.handleChange}
                                />
                                <label className="form-check-label" htmlFor="toImproveMentalHealth">Mejorar salud mental</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="fixShoulder"
                                    checked={formik.values.fixShoulder || false}
                                    onChange={formik.handleChange}
                                />
                                <label className="form-check-label" htmlFor="fixShoulder">Corregir hombros</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="fixKnees"
                                    checked={formik.values.fixKnees || false}
                                    onChange={formik.handleChange}
                                />
                                <label className="form-check-label" htmlFor="fixKnees">Corregir rodillas</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="fixBack"
                                    checked={formik.values.fixBack || false}
                                    onChange={formik.handleChange}
                                />
                                <label className="form-check-label" htmlFor="fixBack">Corregir espalda</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="rehab"
                                    checked={formik.values.rehab || false}
                                    onChange={formik.handleChange}
                                />
                                <label className="form-check-label" htmlFor="rehab">Rehabilitación</label>
                            </div>
                        </div>
                        <div className="col-md-6 mt-2">
                            <label className="form-label fw-bold">Objetivo alimenticio</label>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="toGainMuscle"
                                    checked={formik.values.toGainMuscle || false}
                                    onChange={() => handleWeightObjectiveChange('toGainMuscle')}
                                />
                                <label className="form-check-label" htmlFor="toGainMuscle">Ganar músculo</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="toMaintainWeight"
                                    checked={formik.values.toMaintainWeight || false}
                                    onChange={() => handleWeightObjectiveChange('toMaintainWeight')}
                                />
                                <label className="form-check-label" htmlFor="toMaintainWeight">Mantener peso</label>
                            </div>
                            <div className="form-check form-switch">
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="toLoseWeight"
                                    checked={formik.values.toLoseWeight || false}
                                    onChange={() => handleWeightObjectiveChange('toLoseWeight')}
                                />
                                <label className="form-check-label" htmlFor="toLoseWeight">Perder peso</label>
                            </div>
                        </div>
                    </div>
                </CardBody>

                <CardFooter className="d-flex justify-content-center">
                    <Button type="submit" size='lg' color='primary'>
                        {isLoading ? <Spinner isSmall /> : `${mode} Usuario`}
                    </Button>
                </CardFooter>
            </form>
        </Fragment>
    )
}

export default UserForm;