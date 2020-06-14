import * as types from "../types";

const initialState = {
    posts:[],
    post:{},
};

export const postReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.GET_POSTS:
            return {
                ...state,
                posts:action.payload,
            }
        default:
            return state;
    }
};