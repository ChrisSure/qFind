import Link from 'next/link'
import Button from "@material-ui/core/Button";
import styles from "./styles.scss";
import {useDispatch, useSelector} from "react-redux";
import {resetForm} from "../../redux/actions/authAction";

const Header = () => {
    const dispatch = useDispatch();
    const {token} = useSelector(state => state.token);

    const resetFormAll = () => {
        dispatch(resetForm());
    };

    const showSignButton = () => {
        if (token) {
            return (<h1>Hello</h1>);
        } else {
            return(
                <Button variant="outlined" color="secondary" className={styles.sign_in_icon}>
                <Link href="/auth/signin">
                    <a onClick={resetFormAll}>SignIn</a>
                </Link>
            </Button>
            );
        }
    }

    return (
        <header>
            <h1>
                <Link href="/">
                    <a>NYFind</a>
                </Link>
            </h1>
            {showSignButton()}
        </header>
    );
}

export default Header;