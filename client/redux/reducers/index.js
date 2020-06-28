import {combineReducers} from "redux";
import {authReducer} from "./authReducer";
import {tokenReducer} from "./tokenReducer";

export default combineReducers({
    auth: authReducer,
    token: tokenReducer,
});