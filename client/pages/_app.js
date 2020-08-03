import React, {useEffect} from "react";
import {Provider, useDispatch, useSelector} from 'react-redux'
import {createWrapper} from "next-redux-wrapper";
import store from '../redux/store';
import { useRouter } from 'next/router';
import {getToken} from "../redux/actions/tokenAction";


const myApp = (props) => {
    const {Component, pageProps} = props;
    const router = useRouter();
    const dispatch = useDispatch();

    useEffect(() => {
        let token = dispatch(getToken());
        token.then((token) => {
            if (token === null && router.pathname.substring(0, 8) === '/cabinet') {
                router.push('/auth/signin');
            } else if (token !== null && router.pathname.substring(0, 5) === '/auth') {
                router.push('/cabinet');
            }
        });
    });

    return (
        <Provider store={store}>
            <Component {...pageProps} />
        </Provider>
    )

}

const makeStore = () => store;
const wrapper = createWrapper(makeStore);

export default wrapper.withRedux(myApp);