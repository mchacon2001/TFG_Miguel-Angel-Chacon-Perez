

export const verifyClass = (formik: any, inputFieldID: string) => {
    return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? 'is-invalid' : '';
}

export const showErrorsType = (formik: any, inputFieldID: string) => {
    return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? <div className="invalid-feedback">{formik.errors[inputFieldID]}</div> : <></>;
}

export const showErrors = (formik: any, inputFieldID: string) => {
    return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? <div className="invalid-feedback">{formik.errors[inputFieldID]} </div> : <></>;
}