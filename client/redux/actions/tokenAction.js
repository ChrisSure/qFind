import * as typesToken from "../types/tokenTypes";

export const setToken = (token) =>async dispatch=>{
    if (token) {
        localStorage.setItem('jwt_token', token);
        dispatch({
            type: typesToken.TOKEN_SET_TOKEN,
            token: token,
        });
    }
}

export const getToken = () =>async dispatch=>{
    let token = localStorage.getItem('jwt_token');
    dispatch({
        type: typesToken.TOKEN_SET_TOKEN,
        token: token,
    });
    return token;
}