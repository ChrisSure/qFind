import * as types from "../../types/auth/userInfoTypes";

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
        case types.USER_INFO_REMOVE_DATA:
            return {
                ...state,
                userEmail:'',
                userId:'',
                userRole:'',
            }
        default:
            return state;
    }
};