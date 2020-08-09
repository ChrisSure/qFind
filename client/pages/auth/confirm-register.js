import React, {useState, useEffect} from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import {confirmRegister} from "../../redux/actions/auth/confirmRegisterAction";
import {useDispatch, useSelector} from "react-redux";
import Alert from "@material-ui/lab/Alert";


const conformRegister = () => {
    const dispatch = useDispatch();
    const {message, status} = useSelector(state => state.confirmRegister);

    useEffect(() => {
        //const queryString = qs.parse(location.search);import * as qs from 'query-string';
        dispatch(confirmRegister(location.search));
    }, []);

    const getMessage = () => {
        if (status === "success") {
            return (<Alert severity="success">{message}</Alert>)
        } else {
            return (<Alert severity="error">{message}</Alert>)
        }
    };

    return (
        <main>
            <Header/>
            <aside>
                {getMessage()}
            </aside>
            <Footer />
        </main>
    )
}

export default conformRegister;