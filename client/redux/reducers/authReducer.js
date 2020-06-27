import * as types from "../types";

const initialState = {
    email: '',
    password: '',
    errors: [],
};

export const authReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.CHANGE_EMAIL:
            return {
                ...state,
                email:action.value,
            }
        case types.CHANGE_PASSWORD:
            return {
                ...state,
                password: action.value
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