import * as typesUserInfo from "../../types/auth/userInfoTypes";
import * as jwt_decode from 'jwt-decode';
import * as typesToken from "../../types/auth/tokenTypes";

export const setUserInfo = (token) =>async dispatch=>{
    if (token) {
        var decoded = jwt_decode(token);
        localStorage.setItem('userInfoEmail', decoded.email);
        localStorage.setItem('userInfoId', decoded.id);
        localStorage.setItem('userInfoRole', decoded.roles[0]);
        dispatch({
            type: typesUserInfo.USER_INFO_SET_DATA,
            userEmail: decoded.email,
            userId: decoded.id,
            userRole: decoded.roles[0],
        });
    }
}

export const getUserEmail = () =>async dispatch=>{
    let email = localStorage.getItem('userInfoEmail');
    dispatch({
        type: typesUserInfo.USER_INFO_SET_DATA,
        userEmail: email,
    });
}

export const clearUserData = () =>async dispatch=>{
    localStorage.removeItem('userInfoEmail');
    localStorage.removeItem('userInfoId');
    localStorage.removeItem('userInfoRole');
    dispatch({
        type: typesUserInfo.USER_INFO_REMOVE_DATA,
    });
}