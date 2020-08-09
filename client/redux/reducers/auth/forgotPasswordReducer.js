import * as typesForgotPassword from "../../types/auth/forgotPasswordTypes";
import * as types from "../../types/auth/authTypes";

const initialState = {
    email: '',
    errors: [],
    message: '',
};

export const forgotPasswordReducer = (state = initialState, action) => {
    switch (action.type) {
        case typesForgotPassword.FORGOT_PASSWORD_CHANGE_EMAIL:
            return {
                ...state,
                email:action.email,
            }
        case typesForgotPassword.FORGOT_PASSWORD_VALIDATION:
            return {
                ...state,
                errors: action.errors,
            }
        case typesForgotPassword.FORGOT_PASSWORD_SUCCESS:
            return {
                ...state,
                message:action.message,
            }
        case typesForgotPassword.FORGOT_PASSWORD_RESET_FORM:
            return {
                email: '',
                message: '',
                errors: [],
            }
        default:
            return state;
    }
};