import Link from 'next/link'
import Button from "@material-ui/core/Button";
import styles from "./styles.scss";
import {useDispatch, useSelector} from "react-redux";
import {resetForm} from "../../redux/actions/authAction";
import {getToken} from "../../redux/actions/tokenAction";
import {useEffect} from "react";

const Header = () => {
    const dispatch = useDispatch();
    const {token} = useSelector(state => state.token);

    useEffect(() => {
        dispatch(getToken());
        console.log(token);
    })

    const resetFormAll = () => {
        dispatch(resetForm());
    };

    const showSignButton = () => {
        //dispatch(getToken());

        console.log(token);

    }

    return (
        <header>
            <h1>
                <Link href="/">
                    <a>NYFind</a>
                </Link>
            </h1>
            {/*<Button variant="outlined" color="secondary" className={styles.sign_in_icon}>
                <Link href="/auth/signin">
                    <a onClick={resetFormAll}>SignIn</a>
                </Link>
            </Button>*/}
            {showSignButton()}
        </header>
    );
}

export default Header;