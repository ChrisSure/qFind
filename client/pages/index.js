import Head from 'next/head'
import styles from './index.scss';
import Header from "../components/header/header";
import Footer from "../components/footer/footer";
import {useDispatch, useSelector} from "react-redux";
import {useEffect} from "react";
import {getPosts} from "../redux/actions/postAction";


export default function Home() {
    /*const dispatch = useDispatch();
    const {posts} = useSelector(state => state.post);

    useEffect(() => {
        dispatch(getPosts());
    }, []);*/

  return (
    <div className={styles.container}>
      <Head>
        <title>Create Next App</title>
        <link rel="icon" href="/favicon.ico" />
      </Head>

      <main>
          {/*{posts && posts.map(item => (
              <h2 key={item}>{item}</h2>
          ))}*/}
          <Header/>
          <Footer />
      </main>
    </div>
  )
}
