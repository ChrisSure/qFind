import * as types from "../types";
import Base from "../helpers/Base";
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

    if (validation.errors.length === 0) {
        signin(email, password);
        dispatch(resetForm());
    }
    dispatch({
        type: types.AUTH_VALIDATION,
        errors: validation.errors,
    });
};

function signin(email, password) {
    const params = new URLSearchParams();
    params.append('email', email);
    params.append('password', password);
    params.append('type', 'site');

    axios({
        method: 'post',
        url: 'http://localhost:9999/auth/signin',
        data: params
    })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
}

const resetForm = () =>async dispatch=> {
    dispatch({
        type: types.CHANGE_EMAIL,
        value: '',
    });
    dispatch({
        type: types.CHANGE_PASSWORD,
        value: '',
    });
}