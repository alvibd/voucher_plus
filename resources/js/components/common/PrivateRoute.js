import React, {useContext} from 'react';
import { Route, Redirect } from 'react-router-dom';

import Skeleton from '@material-ui/lab/Skeleton';

import { AuthContext } from '../context/AuthContext'

import {CoffeeLoading} from 'react-loadingg'
import { route } from './Routes';



function PrivateRoute ({ component: Component, ...rest }) {
    // const {state} = useContext(AuthContext)
    return <AuthContext.Consumer>{({state}) =>
    <Route
    {...rest}
    render={(props) => {
      if (state.isLoading) {
        return <CoffeeLoading/>;
      } else if (!state.isAuthenticated) {
        return <Redirect to={route('login')} state={state}/>;
      } else {
        return <Component {...props} />;
      }
    }}
  />
  }</AuthContext.Consumer>

    // return <Route
    //   {...rest}
    //   render={(props) => {
    //     if (state.isLoading) {
    //       return <h2>Loading...</h2>;
    //     } else if (!state.isAuthenticated) {
    //       return <Redirect to="/login" />;
    //     } else {
    //       return <Component {...props} />;
    //     }
    //   }}
    // />
}

export default PrivateRoute
