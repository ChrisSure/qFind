import {combineReducers} from "redux";
import {authReducer} from "./authReducer";
import {tokenReducer} from "./tokenReducer";
import {confirmRegisterReducer} from "./confirmRegisterReducer";

export default combineReducers({
    auth: authReducer,
    token: tokenReducer,
    confirmRegister: confirmRegisterReducer,
});