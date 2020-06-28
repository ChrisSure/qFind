import * as types from "../types/tokenTypes";

const initialState = {
    token: '',
};

export const tokenReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.TOKEN_SET_TOKEN:
            console.log(action.token);
            return {
                ...state,
                token:action.token,
            }
        default:
            return state;
    }
};