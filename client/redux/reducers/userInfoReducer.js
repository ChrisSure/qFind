import * as types from "../types/userInfoTypes";

const initialState = {
    userEmail: '',
    userId: '',
    userRole: '',
};

export const userInfoReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.USER_INFO_SET_DATA:
            return {
                ...state,
                userEmail:action.userEmail,
                userId:action.userId,
                userRole:action.userRole,
            }
        default:
            return state;
    }
};