import React, {useState, useEffect} from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from '../index.scss';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import Link from "next/link";
import Grid from '@material-ui/core/Grid';
import {useDispatch, useSelector} from "react-redux";
import * as types from "../../redux/types/authTypes";
import {authValidation, resetForm} from "../../redux/actions/authAction";
import {signin} from "../../redux/actions/authAction";
import Alert from '@material-ui/lab/Alert';


const SignIn = (props) => {
    const dispatch = useDispatch();
    const {email, password, errors} = useSelector(state => state.auth);

    const handleOnSubmit = (event) => {
        event.preventDefault();

        let validationErrors = dispatch(authValidation(email, password));
        validationErrors.then((count) => {
            if (count === 0) {a
                dispatch(signin(email, password));
                dispatch(resetForm());
            }
        });
    };

    const handleOnChangeEmail = (event) => {
        dispatch({type: types.AUTH_CHANGE_EMAIL, email: event.target.value});
    };

    const handleOnChangePassword = (event) => {
        dispatch({type: types.AUTH_CHANGE_PASSWORD, password: event.target.value});
    };

    const resetFormAll = () => {
        dispatch(resetForm());
    };

    return (
        <main>
            <Header/>
            <aside>
                {errors && errors.map(item => (
                    <Alert key={item} severity="error">{item}</Alert>
                ))}
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
                                        value={email}
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
                                        value={password}
                                        onChange={handleOnChangePassword}
                                    />
                                </li>
                                <li>
                                    <Button type="submit" variant="contained" color="primary">Login</Button>
                                </li>
                                <li className={styles.forgot}>
                                    <Link href="/forgot-password">Forgot password</Link>
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
                                        <a onClick={resetFormAll}> Create account</a>
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