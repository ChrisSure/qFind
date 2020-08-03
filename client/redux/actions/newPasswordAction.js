import axios from "axios";
import * as typesNewPassword from "../types/newPasswordTypes";
import Base from "../helpers/Validation";
import {setToken} from "./tokenAction";

export const confirmNewPassword = (queryString) =>async dispatch=> {
    axios({
        method: 'get',
        url: 'http://localhost:9999/auth/confirm-new-password' + queryString
    }).then(function (response) {

    }).catch(function (error) {
        let errorText = (typeof error.response.data.error !== "undefined") ? error.response.data.error : error.response.data.detail;
        dispatch({
            type: typesNewPassword.NEW_PASSWORD_CONFIRM_FAILURE,
            message: errorText,
            status: "error"
        });
    });
}

export const newPasswordValidation = (password, password_repeat) =>async dispatch=>{
    const fieldPassword = "password";

    let validation = new Base();
    validation.isRequire(fieldPassword, password);
    validation.isString(fieldPassword, password);
    validation.minString(fieldPassword, password, 2);
    validation.isCompare(password, password_repeat);

    dispatch({
        type: typesNewPassword.NEW_PASSWORD_VALIDATION,
        errors: validation.errors,
    });

    return validation.errors.length;
};

export const newPasswordSend = (id, password) =>async dispatch=> {
    const params = new URLSearchParams();
    params.append('password', password);

    axios({
        method: 'post',
        url: 'http://localhost:9999/auth/new-password/' + id,
        data: params
    }).then(function (response) {
        dispatch(setToken(response.data.token));
    }).catch(function (error) {
        dispatch({
            type: typesNewPassword.NEW_PASSWORD_VALIDATION,
            errors: [error.response.data.error],
        });
    });
}

export const resetForm = () =>async dispatch=> {
    dispatch({
        type: typesNewPassword.NEW_PASSWORD_RESET_FORM,
    });
}