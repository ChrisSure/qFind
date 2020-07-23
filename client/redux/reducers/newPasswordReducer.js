import * as typesNewPassword from "../types/newPasswordTypes";

const initialState = {
    password: '',
    password_repeat: '',
    errors: [],
    message: '',
    status: '',
};

export const newPasswordReducer = (state = initialState, action) => {
    switch (action.type) {
        case typesNewPassword.NEW_PASSWORD_CHANGE_PASSWORD:
            return {
                ...state,
                password:action.password,
            }
        case typesNewPassword.NEW_PASSWORD_CHANGE_PASSWORD_REPEAT:
            return {
                ...state,
                password_repeat:action.password_repeat,
            }
        case typesNewPassword.NEW_PASSWORD_CONFIRM_FAILURE:
            return {
                ...state,
                message:action.message,
                status: action.status
            }
        case typesNewPassword.NEW_PASSWORD_VALIDATION:
            return {
                ...state,
                errors: action.errors,
            }
        case typesNewPassword.NEW_PASSWORD_RESET_FORM:
            return {
                password: '',
                password_repeat: '',
                message: '',
                errors: [],
                status: '',
            }
        default:
            return state;
    }
};