import {combineReducers} from "redux";
import {authReducer} from "./auth/authReducer";
import {tokenReducer} from "./auth/tokenReducer";
import {confirmRegisterReducer} from "./auth/confirmRegisterReducer";
import {forgotPasswordReducer} from "./auth/forgotPasswordReducer";
import {newPasswordReducer} from "./auth/newPasswordReducer";
import {userInfoReducer} from "./auth/userInfoReducer";

export default combineReducers({
    auth: authReducer,
    token: tokenReducer,
    confirmRegister: confirmRegisterReducer,
    forgotPassword: forgotPasswordReducer,
    newPassword: newPasswordReducer,
    userInfoReducer: userInfoReducer
});