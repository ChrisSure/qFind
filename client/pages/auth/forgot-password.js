import React, {useState, useEffect} from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from "../index.scss";
import TextField from "@material-ui/core/TextField";
import Button from "@material-ui/core/Button";
import {useDispatch, useSelector} from "react-redux";
import * as typesForgotPassword from "../../redux/types/auth/forgotPasswordTypes";
import {forgotPasswordValidation, forgotPasswordSend, resetForm} from "../../redux/actions/auth/forgotPasswordAction";
import Alert from "@material-ui/lab/Alert";



const forgotPassword = () => {
    const dispatch = useDispatch();
    const {email, errors, message} = useSelector(state => state.forgotPassword);

    const handleOnSubmit = (event) => {
        event.preventDefault();

        let validationErrors = dispatch(forgotPasswordValidation(email));
        validationErrors.then((count) => {
            if (count === 0) {
                dispatch(forgotPasswordSend(email));
                dispatch(resetForm());
            }
        });
    };

    const handleOnChangeEmail = (event) => {
        dispatch({type: typesForgotPassword.FORGOT_PASSWORD_CHANGE_EMAIL, email: event.target.value});
    };

    return (
        <main>
            <Header/>
            <aside>
                {errors && errors.map(item => (
                    <Alert key={item} severity="error">{item}</Alert>
                ))}
                {message !== '' ? (<Alert severity="success">{message}</Alert>) : null}
                <h1>Forgot password</h1>
                <form type="POST" className={styles.root} noValidate autoComplete="off" onSubmit={handleOnSubmit}>
                    <ul>
                        <li>
                            <TextField
                                id="outlined-basic-email"
                                label="Email"
                                variant="outlined"
                                name="email"
                                value={email}
                                onChange={handleOnChangeEmail}
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

export default forgotPassword;