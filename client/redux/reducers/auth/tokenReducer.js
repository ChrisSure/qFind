import * as types from "../../types/auth/tokenTypes";

const initialState = {
    token: '',
};

export const tokenReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.TOKEN_SET_TOKEN:
            return {
                ...state,
                token:action.token,
            }
        case types.TOKEN_REMOVE_TOKEN:
            return {
                ...state,
                token:'',
            }
        default:
            return state;
    }
};