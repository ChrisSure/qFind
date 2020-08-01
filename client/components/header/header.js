import Link from 'next/link'
import Button from "@material-ui/core/Button";
import styles from "./styles.scss";
import {useDispatch} from "react-redux";
import {resetForm} from "../../redux/actions/authAction";

const Header = () => {
    const dispatch = useDispatch();

    const resetFormAll = () => {
        dispatch(resetForm());
    };

    return (
        <header>
            <h1>
                <Link href="/">
                    <a>NYFind</a>
                </Link>
            </h1>
            <Button variant="outlined" color="secondary" className={styles.sign_in_icon}>
                <Link href="/auth/signin">
                    <a onClick={resetFormAll}>SignIn</a>
                </Link>
            </Button>
        </header>
    );
}

export default Header;