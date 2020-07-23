import * as typesConfirmRegister from "../types/confirmRegisterTypes";

const initialState = {
    message: '',
    status: '',
};

export const confirmRegisterReducer = (state = initialState, action) => {
    switch (action.type) {
        case typesConfirmRegister.CONFIRM_REGISTER_SUCCESS:
            return {
                ...state,
                message:action.message,
                status: action.status
            }
        case typesConfirmRegister.CONFIRM_REGISTER_FAILURE:
            return {
                ...state,
                message:action.message,
                status: action.status
            }
        default:
            return state;
    }
};