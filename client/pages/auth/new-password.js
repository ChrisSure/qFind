import React, {useState, useEffect} from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from "../index.scss";
import TextField from "@material-ui/core/TextField";
import Button from "@material-ui/core/Button";
import {useDispatch, useSelector} from "react-redux";
import * as typesNewPassword from "../../redux/types/newPasswordTypes";
import Alert from "@material-ui/lab/Alert";
import {confirmNewPassword, newPasswordValidation, newPasswordSend, resetForm} from "../../redux/actions/newPasswordAction";
import * as qs from 'query-string';
import Router from 'next/router'


const newPassword = () => {
    const dispatch = useDispatch();
    const {password, password_repeat, errors, message, status} = useSelector(state => state.newPassword);

    useEffect(() => {
        dispatch(confirmNewPassword(location.search));
    }, []);

    const handleOnSubmit = (event) => {
        event.preventDefault();

        let validationErrors = dispatch(newPasswordValidation(password, password_repeat));
        validationErrors.then((count) => {
            if (count === 0) {
                const id = qs.parse(location.search).id;
                dispatch(newPasswordSend(id, password));
                dispatch(resetForm());

                Router.push('/');
            }
        });

    };

    const handleOnChangePassword = (event) => {
        dispatch({type: typesNewPassword.NEW_PASSWORD_CHANGE_PASSWORD, password: event.target.value});
    };

    const handleOnChangePasswordRepeat = (event) => {
        dispatch({type: typesNewPassword.NEW_PASSWORD_CHANGE_PASSWORD_REPEAT, password_repeat: event.target.value});
    };

    const getMessage = () => {
        if (message !== '') {
            if (status === "success") {
                return (<Alert severity="success">{message}</Alert>)
            } else {
                return (<Alert severity="error">{message}</Alert>)
            }
        }
    };

    return (
        <main>
            <Header/>
            <aside>
                {errors && errors.map(item => (
                    <Alert key={item} severity="error">{item}</Alert>
                ))}
                {getMessage()}
                <h1>New password</h1>
                <form type="POST" className={styles.root} noValidate autoComplete="off" onSubmit={handleOnSubmit}>
                    <ul>
                        <li>
                            <TextField
                                id="outlined-basic-password"
                                label="Password"
                                variant="outlined"
                                type="password"
                                name="password"
                                value={password}
                                onChange={handleOnChangePassword}
                            />
                        </li>
                        <li>
                            <TextField
                                id="outlined-basic-password_repeat"
                                label="Password repeat"
                                variant="outlined"
                                type="password"
                                name="password_repeat"
                                value={password_repeat}
                                onChange={handleOnChangePasswordRepeat}
                            />
                        </li>
                        <li>
                            <Button type="submit" variant="contained" color="primary">Submit</Button>
                        </li>
                    </ul>
                </form>
            </aside>
            <Footer />
        </main>
    )
}

export default newPassword;