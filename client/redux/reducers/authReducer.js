import * as types from "../types";

const initialState = {
    email: '',
    password: '',
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
        default:
            return state;
    }
};