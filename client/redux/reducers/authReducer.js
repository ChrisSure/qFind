import * as types from "../types/authTypes";

const initialState = {
    email: '',
    password: '',
    errors: [],
    message: '',
};

export const authReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.AUTH_CHANGE_EMAIL:
            return {
                ...state,
                email:action.email,
            }
        case types.AUTH_CHANGE_PASSWORD:
            return {
                ...state,
                password: action.password
            }
        case types.AUTH_VALIDATION:
            return {
                ...state,
                errors: action.errors,
            }
        case types.AUTH_SIGNUP_SUCCESS:
            return {
                ...state,
                message: action.message,
            }
        case types.AUTH_RESET_FORM:
            return {
                email: '',
                password: '',
                message: '',
                errors: [],
            }
        default:
            return state;
    }
};