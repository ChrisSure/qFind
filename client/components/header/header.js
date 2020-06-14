import Link from 'next/link'
import Button from "@material-ui/core/Button";
import styles from "./styles.scss";

const Header = () => (
    <header>
        <h1>
            <Link href="/">
                <a>NYFind</a>
            </Link>
        </h1>
        <Button variant="outlined" color="secondary" className={styles.sign_in_icon}>
            <Link href="/signin">
                <a>SignIn</a>
            </Link>
        </Button>
    </header>
)

export default Header