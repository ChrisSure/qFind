import axios from 'axios';
import * as typesConfirmRegister from "../../types/auth/confirmRegisterTypes";
import {setToken} from "./tokenAction";

export const confirmRegister = (queryString) =>async dispatch=> {
    axios({
        method: 'get',
        url: 'http://localhost:9999/auth/confirm-register' + queryString
    }).then(function (response) {
        dispatch({
            type: typesConfirmRegister.CONFIRM_REGISTER_SUCCESS,
            message: "You confirmed successfull registration",
            status: "success"
        });
        dispatch(setToken(response.data.token));
    }).catch(function (error) {
        let errorText = (typeof error.response.data.error !== "undefined") ? error.response.data.error : error.response.data.detail;
        dispatch({
            type: typesConfirmRegister.CONFIRM_REGISTER_FAILURE,
            message: errorText,
            status: "error"
        });
    });
}
