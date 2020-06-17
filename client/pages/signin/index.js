import React, {useState} from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from './styles.scss';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import Link from "next/link";
import Grid from '@material-ui/core/Grid';
import {useDispatch, useSelector} from "react-redux";
import * as types from "../../redux/types";
import {signin} from "../../redux/actions/authAction";
import authValidation from "../../validation/auth/authValidation";


const SignIn = (props) => {
    const dispatch = useDispatch();
    const authStore = useSelector(state => state.auth);
    let errorsValidation = [];

    const handleOnSubmit = (event) => {
        event.preventDefault();

        let errors = authValidation(authStore);
        if (errors.length > 0) {
            errorsValidation = errors;
        } else {
            dispatch(signin(authStore));
        }
    };

    const handleOnChangeEmail = (event) => {
        dispatch({type: types.CHANGE_EMAIL, value: event.target.value});
    };

    const handleOnChangePassword = (event) => {
        dispatch({type: types.CHANGE_PASSWORD, value: event.target.value});
    };

    const getErrors = (errorsValidation) => {
        console.log(errorsValidation);
        if (errorsValidation.length > 0) {
            return (<p>Ok</p>);
        }
    };

    return (
        <main>
            <Header/>
            <aside>
                {getErrors(errorsValidation)}
                <Grid container>
                    <Grid item xs={6}>
                        <h1>SignIn</h1>
                        <form type="POST" className={styles.root} noValidate autoComplete="off" onSubmit={handleOnSubmit}>
                            <ul>
                                <li>
                                    <TextField
                                        id="outlined-basic-email"
                                        label="Email"
                                        variant="outlined"
                                        name="email"
                                        value={props.email}
                                        onChange={handleOnChangeEmail}
                                    />
                                </li>
                                <li>
                                    <TextField
                                        id="outlined-basic-password"
                                        label="Password"
                                        variant="outlined"
                                        type="password"
                                        name="password"
                                        value={props.email}
                                        onChange={handleOnChangePassword}
                                    />
                                </li>
                                <li>
                                    <Button type="submit" variant="contained" color="primary">Login</Button>
                                </li>
                            </ul>
                        </form>
                    </Grid>
                    <Grid item xs={6}>
                        <h1>New Customer</h1>
                        <form className={styles.root} noValidate autoComplete="off">
                        <ul>
                            <li>
                                <Button variant="contained" color="secondary">
                                    <Link href="/signup">
                                        <a> Create account</a>
                                    </Link>
                                </Button>
                            </li>
                        </ul>
                        </form>
                    </Grid>
                </Grid>
            </aside>
            <Footer />
        </main>
    )
}

export default SignIn;