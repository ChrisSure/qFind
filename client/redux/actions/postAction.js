import * as types from "../types";

export const getposts = () =>async dispatch=>{
    dispatch({
        type: types.GET_POSTS,
        payload: ['1post', '2post'],
    });
}