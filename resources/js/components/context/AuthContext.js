import React, { createContext, useReducer } from 'react'

const AuthState = {
    isAuthenticated : localStorage.getItem('token') != null ? true : false,
    user: null,
    access_token : localStorage.getItem('token') != null ? localStorage.getItem('token') : null,
    isLoading: false
}

 //Create Context
 export const AuthContext = createContext(AuthState)

 //Provider component
 export const AuthProvider = ({ children }) => {
    const [state, dispatch] = useReducer(AuthReducer, AuthState)

    return (
    <AuthContext.Provider value={{state, dispatch}}>
        { children }
    </AuthContext.Provider>
    )
}

function AuthReducer(state,action){
    switch (action.type) {
        case "REFRESH_TOKEN":
        window.localStorage.setItem('token', action.payload.access_token);
        return {
            ...state,
            access_token: action.payload.access_token,
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

