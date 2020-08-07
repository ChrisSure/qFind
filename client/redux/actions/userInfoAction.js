import * as typesUserInfo from "../types/userInfoTypes";
import * as jwt_decode from 'jwt-decode';

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