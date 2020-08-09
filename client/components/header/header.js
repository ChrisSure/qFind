import Link from 'next/link'
import Button from "@material-ui/core/Button";
import styles from "./styles.scss";
import {useDispatch, useSelector} from "react-redux";
import {resetForm} from "../../redux/actions/auth/authAction";
import {useEffect} from "react";
import {clearUserData, getUserEmail} from "../../redux/actions/auth/userInfoAction";
import {removeToken} from "../../redux/actions/auth/tokenAction";
import {useRouter} from "next/router";

const Header = () => {
    const dispatch = useDispatch();
    const router = useRouter();
    const {token} = useSelector(state => state.token);
    const {userEmail} = useSelector(state => state.userInfoReducer);

    useEffect(() => {
        dispatch(getUserEmail());
    }, []);

    const resetFormAll = () => {
        dispatch(resetForm());
    };

    const logout = () => {
        dispatch(removeToken());
        dispatch(clearUserData());
        router.push('/');
    }

    const showSignButton = () => {
        if (token) {
            return (
                <div>
                    <h4 className={styles.userEmail}>{userEmail}</h4><Button onClick={logout} variant="outlined" color="secondary" className={styles.sign_in_icon}>Logout</Button>
                </div>
            );
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