import Head from 'next/head'
import styles from './index.scss';
import Header from "../components/header/header";
import Footer from "../components/footer/footer";
import Link from "next/link";


export default function Home() {

  return (
    <div className={styles.container}>
      <Head>
        <title>Create Next App</title>
        <link rel="icon" href="/favicon.ico" />
      </Head>

      <main>
          <Header/>
            <aside>
                <Link href="/cabinet/home">
                    <a>Cabinet</a>
                </Link>
            </aside>
          <Footer />
      </main>
    </div>
  )
}
