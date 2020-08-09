import React from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from '../index.scss';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import {authValidation, resetForm, signup} from "../../redux/actions/auth/authAction";
import * as types from "../../redux/types/auth/authTypes";
import {useDispatch, useSelector} from "react-redux";
import Alert from "@material-ui/lab/Alert";


const SignUp = () => {
    const dispatch = useDispatch();
    const {email, password, errors, message} = useSelector(state => state.auth);

    const handleOnSubmit = (event) => {
        event.preventDefault();

        let validationErrors = dispatch(authValidation(email, password));
        validationErrors.then((count) => {
            if (count === 0) {
                dispatch(signup(email, password));
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

    return (
        <main>
            <Header/>
            <aside>
                {errors && errors.map(item => (
                    <Alert key={item} severity="error">{item}</Alert>
                ))}
                {message !== '' ? (<Alert severity="success">{message}</Alert>) : null}
                <h1>SignUp</h1>
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
            </aside>
            <Footer />
        </main>
    )
}

export default SignUp