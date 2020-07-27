import axios from 'axios'
// import { returnErrors } from './messages'

// CHECK TOKEN & LOAD USER
export const loadUser = (dispatch, state) => {

    // User Loading
    dispatch({ type: "USER_LOADING" });

    return axios
    .post('/api/me', null,tokenConfig(state))
    .then((res) => {
        dispatch({
            type: "USER_LOADED",
            payload: res.data,
        });
    })
    .catch((err) => {
        // dispatch(returnErrors(err.response.data, err.response.status));
        dispatch({
            type: "AUTH_ERROR",
        });
        throw err
    });
};

// LOGIN USER
export const login = (email, password) => (dispatch) => {

    dispatch({
        type: "USER_LOADING",
        payload: null,
    });
    // Headers
    const config = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    // Request Body
    const body = JSON.stringify({ email, password });

    return axios
    .post('/api/login', body, config)
    .then((res) => {
        dispatch({
            type: "LOGIN_SUCCESS",
            payload: res.data,
        });
    })
    .catch((err) => {

        // dispatch(returnErrors(err.response.data, err.response.status));
        dispatch({
            type: "LOGIN_FAIL",
        });
        throw err;
    })
};

// LOGOUT USER
export const logout = (dispatch, state) => {
    return axios
    .post('/api/logout/', null, tokenConfig(state))
    .then((res) => {
        // dispatch({ type: 'CLEAR_LEADS' });
        dispatch({
            type: "LOGOUT_SUCCESS",
        });
    })
    // .catch((err) => {
    //     // dispatch(returnErrors(err.response.data, err.response.status));
    // });
};

// LOGIN USER
export const registration = (dispatch, name, email, password, password_confirmation, sex) => {
    // Headers
    const config = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    // Request Body
    const body = JSON.stringify({ name, email, password, password_confirmation, sex });

    return axios
    .post('/api/register', body, config)
    .then((res) => {
        dispatch({
            type: "REGISTER_SUCCESS",
            payload: res.data,
        });
    })
    .catch((err) => {

        // dispatch(returnErrors(err.response.data, err.response.status));
        dispatch({
            type: "REGISTER_FAIL",
        });
        throw err;
    })
};

// Setup config with token - helper function
const tokenConfig = (state) => {
    // Get token from state
    const token = state.access_token;

    // Headers
    const config = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    // If token, add to headers config
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }

    return config;
};
