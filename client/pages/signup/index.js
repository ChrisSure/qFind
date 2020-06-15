import React from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from './styles.scss';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';


const SignUp = () => {
    return (
        <main>
            <Header/>
            <aside>
                <h1>SignUp</h1>
                <form className={styles.root} noValidate autoComplete="off">
                    <ul>
                        <li><TextField id="outlined-basic" label="Email" variant="outlined" /></li>
                        <li><TextField id="outlined-basic" label="Password" variant="outlined" type="password" /></li>
                        <li>
                            <Button variant="contained" color="primary">Create account</Button>
                        </li>
                    </ul>
                </form>
            </aside>
            <Footer />
        </main>
    )
}

export default SignUp