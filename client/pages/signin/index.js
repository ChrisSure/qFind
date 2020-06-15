import React from "react";
import Header from "../../components/header/header";
import Footer from "../../components/footer/footer";
import styles from './styles.scss';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import Link from "next/link";


const SignIn = () => {
    return (
        <main>
            <Header/>
            <aside>
                <h1>SignIn</h1>
                <form className={styles.root} noValidate autoComplete="off">
                    <ul>
                        <li><TextField id="outlined-basic" label="Email" variant="outlined" /></li>
                        <li><TextField id="outlined-basic" label="Password" variant="outlined" type="password" /></li>
                        <li>
                            <Button variant="contained" color="primary">Login</Button>
                        </li>
                        <li>
                            <Button variant="contained" color="secondary">
                                <Link href="/signup">
                                    <a> Create account</a>
                                </Link>
                            </Button>
                        </li>
                    </ul>
                </form>
            </aside>
            <Footer />
        </main>
    )
}

export default SignIn