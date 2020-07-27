import React, { createContext, useReducer } from 'react'

const GolbalState = {
    isAuthenticated : localStorage.getItem('token') != null ? true : false,
    user: null,
    access_token : localStorage.getItem('token') != null ? localStorage.getItem('token') : null,
    isLoading: false
}

//Create Context
export const GlobalContext = createContext(GolbalState)

//Provider component
export const GlobalProvider = ({ children }) => {
    const [state, dispatch] = useReducer(GlobalReducer, GolbalState)

    return (
    <GlobalContext.Provider value={{state, dispatch}}>
        { children }
    </GlobalContext.Provider>
    )
}

function GlobalReducer(state,action){
    switch (action.type) {
        case "REFRESH_TOKEN":
            window.localStorage.setItem('token', action.payload.access_token);

            return {
                ...state,
                user: action.payload.access_token,
                isAuthenticated: true,
                isLoading: false,
            };
        case "USER_LOADING":
            return {
                ...state,
                isLoading: true,
            };
        case "USER_LOADED":
            return {
                ...state,
                user: action.payload.name,
                isAuthenticated: true,
                isLoading: false,
            };
        case "LOGIN_SUCCESS":
        case "REGISTER_SUCCESS":
            window.localStorage.setItem('token', action.payload.access_token);
            return {
                ...state,
                // ...action.payload,
                access_token: action.payload.access_token,
                isAuthenticated: true,
                isLoading: false,
                user: action.payload.name,
            };
        case "AUTH_ERROR":
        case "LOGIN_FAIL":
        case "LOGOUT_SUCCESS":
        case "REGISTER_FAIL":
            // console.log('deleting token')
            window.localStorage.removeItem('token');
            return {
                ...state,
                access_token: null,
                user: null,
                isAuthenticated: false,
                isLoading: false,
            };
    }
}

