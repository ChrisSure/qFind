import * as typesAuth from "../types/authTypes";
import * as typesToken from "../types/tokenTypes";
import Base from "../helpers/Validation";
import axios from 'axios';


export const authValidation = (email, password) =>async dispatch=>{
    const fieldEmail = "email";
    const fieldPassword = "password";

    let validation = new Base();
    validation.isRequire(fieldEmail, email);
    validation.email(fieldEmail, email);
    validation.isString(fieldEmail, email);
    validation.minString(fieldEmail, email, 3);

    validation.isRequire(fieldPassword, password);
    validation.isString(fieldPassword, password);
    validation.minString(fieldPassword, password, 2);

    dispatch({
        type: typesAuth.AUTH_VALIDATION,
        errors: validation.errors,
    });

    return validation.errors.length;
};

export const signin = (email, password) =>async dispatch=> {
    const params = new URLSearchParams();
    params.append('email', email);
    params.append('password', password);
    params.append('type', 'site');

    axios({
        method: 'post',
        url: 'http://localhost:9999/auth/signin',
        data: params
    }).then(function (response) {
            dispatch({
                type: typesToken.TOKEN_SET_TOKEN,
                token: response.data.token,
            });
    }).catch(function (error) {
        dispatch({
            type: typesAuth.AUTH_VALIDATION,
            errors: [error.response.data.error],
        });
    });
}

export const signup = (email, password) =>async dispatch=> {
    const params = new URLSearchParams();
    params.append('email', email);
    params.append('password', password);
    params.append('type', 'site');

    axios({
        method: 'post',
        url: 'http://localhost:9999/auth/signup',
        data: params
    }).then(function (response) {
        dispatch({
            type: typesAuth.AUTH_SIGNUP_SUCCESS,
            message: response.data.message,
        });
    }).catch(function (error) {
        dispatch({
            type: typesAuth.AUTH_VALIDATION,
            errors: [error.response.data.error],
        });
    });
}

export const resetForm = () =>async dispatch=> {
    dispatch({
        type: typesAuth.AUTH_RESET_FORM,
    });
}