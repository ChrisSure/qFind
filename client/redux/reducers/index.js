import {combineReducers} from "redux";
import {authReducer} from "./authReducer";
import {tokenReducer} from "./tokenReducer";
import {confirmRegisterReducer} from "./confirmRegisterReducer";
import {forgotPasswordReducer} from "./forgotPasswordReducer";
import {newPasswordReducer} from "./newPasswordReducer";
import {userInfoReducer} from "./userInfoReducer";

export default combineReducers({
    auth: authReducer,
    token: tokenReducer,
    confirmRegister: confirmRegisterReducer,
    forgotPassword: forgotPasswordReducer,
    newPassword: newPasswordReducer,
    userInfoReducer: userInfoReducer
});