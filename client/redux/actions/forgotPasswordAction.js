import Base from "../helpers/Validation";
import * as typesForgotPassword from "../types/forgotPasswordTypes";
import axios from "axios";


export const forgotPasswordValidation = (email) =>async dispatch=>{
    const fieldEmail = "email";

    let validation = new Base();
    validation.isRequire(fieldEmail, email);
    validation.email(fieldEmail, email);
    validation.isString(fieldEmail, email);
    validation.minString(fieldEmail, email, 3);

    dispatch({
        type: typesForgotPassword.FORGOT_PASSWORD_VALIDATION,
        errors: validation.errors,
    });

    return validation.errors.length;
};

export const forgotPasswordSend = (email) =>async dispatch=> {
    const params = new URLSearchParams();
    params.append('email', email);

    axios({
        method: 'post',
        url: 'http://localhost:9999/auth/forget-password',
        data: params
    }).then(function (response) {
        dispatch({
            type: typesForgotPassword.FORGOT_PASSWORD_SUCCESS,
            message: response.data.message,
        });
    }).catch(function (error) {
        dispatch({
            type: typesForgotPassword.FORGOT_PASSWORD_VALIDATION,
            errors: [error.response.data.error],
        });
    });
}

export const resetForm = () =>async dispatch=> {
    dispatch({
        type: typesForgotPassword.FORGOT_PASSWORD_RESET_FORM,
    });
}