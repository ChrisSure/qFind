import * as types from "../types/authTypes";

const initialState = {
    email: '',
    password: '',
    errors: [],
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
            
        default:
            return state;
    }
};