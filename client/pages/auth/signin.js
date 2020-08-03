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
import {authValidation, resetForm, socialSignIn} from "../../redux/actions/authAction";
import {signin} from "../../redux/actions/authAction";
import Alert from '@material-ui/lab/Alert';
import FacebookLogin from 'react-facebook-login';
import GoogleLogin from 'react-google-login';
import {SocialUser} from "../../models/auth/SocialUser";
import {useRouter} from "next/router";


const SignIn = () => {
    const dispatch = useDispatch();
    const router = useRouter();
    const {email, password, errors} = useSelector(state => state.auth);

    const handleOnSubmit = (event) => {
        event.preventDefault();

        let validationErrors = dispatch(authValidation(email, password));
        validationErrors.then((count) => {
            if (count === 0) {
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

    const responseFacebook = (response) => {
        let socialUser = new SocialUser();
        socialUser.email = response.email;
        socialUser.provider = 'facebook';
        socialUser.name = response.name;
        socialUser.image = response.picture.data.url;
        socialUser.appId = response.id;

        dispatch(socialSignIn(socialUser));
    }

    const responseGoogle = (response) => {
        let socialUser = new SocialUser();
        socialUser.email = response.profileObj.email;
        socialUser.provider = 'google';
        socialUser.name = response.profileObj.name;
        socialUser.image = response.profileObj.imageUrl;
        socialUser.appId = response.googleId;

        dispatch(socialSignIn(socialUser));
    }

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
                                    <Link href="/auth/forgot-password">Forgot password</Link>
                                </li>
                                <li>
                                    <FacebookLogin
                                        appId="1541183036061327" //APP ID NOT CREATED YET
                                        fields="name,email,picture"
                                        callback={responseFacebook}
                                    />
                                </li>
                                <li>
                                    <GoogleLogin
                                        clientId="773947189913-lnnd2cd1una5upf96jugghv474bp9pbc.apps.googleusercontent.com" //CLIENTID NOT CREATED YET
                                        buttonText="LOGIN WITH GOOGLE"
                                        onSuccess={responseGoogle}
                                        onFailure={responseGoogle}
                                    />
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
                                    <Link href="/auth/signup">
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