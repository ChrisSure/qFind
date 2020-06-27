import React, {useState, useEffect} from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from './styles.scss';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import Link from "next/link";
import Grid from '@material-ui/core/Grid';
import {useDispatch, useSelector} from "react-redux";
import * as types from "../../redux/types";
import {authValidation, signin} from "../../redux/actions/authAction";
import Alert from '@material-ui/lab/Alert';


/*function Alert(props) {
    return <MuiAlert elevation={6} variant="filled" {...props} />;
}*/

const SignIn = (props) => {
    const dispatch = useDispatch();
    const {email, password, errors} = useSelector(state => state.auth);
    const auth = useSelector(state => state.auth);

    const handleOnSubmit = (event) => {
        event.preventDefault();

        dispatch(authValidation(email, password));
    };

    const handleOnChangeEmail = (event) => {
        dispatch({type: types.CHANGE_EMAIL, value: event.target.value});
    };

    const handleOnChangePassword = (event) => {
        dispatch({type: types.CHANGE_PASSWORD, value: event.target.value});
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