import {combineReducers} from "redux";
import {authReducer} from "./authReducer";
import {tokenReducer} from "./tokenReducer";
import {confirmRegisterReducer} from "./confirmRegisterReducer";
import {forgotPasswordReducer} from "./forgotPasswordReducer";
import {newPasswordReducer} from "./newPasswordReducer";

export default combineReducers({
    auth: authReducer,
    token: tokenReducer,
    confirmRegister: confirmRegisterReducer,
    forgotPassword: forgotPasswordReducer,
    newPassword: newPasswordReducer,
});