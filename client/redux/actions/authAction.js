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

    if (validation.errors.length === 0) {
        dispatch(signin(email, password));
        dispatch(resetForm());
    }
    dispatch({
        type: typesAuth.AUTH_VALIDATION,
        errors: validation.errors,
    });
};

const signin = (email, password) =>async dispatch=> {
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
            dispatch({
                type: typesToken.TOKEN_SET_TOKEN,
                token: response.data.token,
            });
        }, (error) => {
            dispatch({
                type: typesAuth.AUTH_VALIDATION,
                errors: [error.response.data.error],
            });
        });
}

const resetForm = () =>async dispatch=> {
    dispatch({
        type: typesAuth.AUTH_CHANGE_EMAIL,
        email: '',
    });
    dispatch({
        type: typesAuth.AUTH_CHANGE_PASSWORD,
        password: '',
    });
}