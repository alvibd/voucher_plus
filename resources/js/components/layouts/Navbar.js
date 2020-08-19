import React, { useContext, Fragment, useEffect } from 'react'
import {Link as RouteLink, Redirect } from 'react-router-dom'

import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import Icon from '@material-ui/core/Icon';
import HomeOutlinedIcon from '@material-ui/icons/HomeOutlined';
import ExitToAppIcon from '@material-ui/icons/ExitToApp';
import AccountCircleRoundedIcon from '@material-ui/icons/AccountCircleRounded';
import AccountBoxIcon from '@material-ui/icons/AccountBox';
import { Link, makeStyles } from '@material-ui/core';
import { useSnackbar } from 'notistack';

import { AuthContext } from '../context/AuthContext';
import {loadUser, logout} from '../actions/auth'
import AdminBar from './AdminBar';
import UserBar from './UserBar';
import {route} from '../common/Routes'


const useStyles = makeStyles((theme) => ({
    navLink : {
        '&:hover' : {
            textDecoration: "none"
        }
    }
}))

export default function Navbar() {
    const classes = useStyles();
    const {state, dispatch} = useContext(AuthContext)
    const {enqueueSnackbar} = useSnackbar()
    // console.log(state)

    useEffect(() => {
        // console.log(state.access_token)
        if((!state.isLoading) && state.access_token!= null && state.user == null){
            // to do implement load user
            loadUser(dispatch, state)
        }
    })

    const handleClick = (e) => {
        e.preventDefault()

        // console.log(this)
        // const {state, dispatch} = this.context

        logout(dispatch, state)
        .catch(err => {
            // console.log("With Error: "+err)
            enqueueSnackbar(err.response, {
                variant : 'error',
                persist: true
            })
        })
        .then((value) => {
            // console.log("it came here")
            return <Redirect to="/" />
        })

    }


    const guestLinks = (
        <Fragment>
            <RouteLink to={route('login')} className={classes.navLink}>
                <ListItem button>
                    <ListItemIcon>
                        <ExitToAppIcon/>
                    </ListItemIcon>
                    <ListItemText primary="Login" />
                </ListItem>
            </RouteLink>
            <RouteLink to="/register">
                <ListItem button>
                    <ListItemIcon>
                        <AccountBoxIcon/>
                    </ListItemIcon>
                    <ListItemText primary="Registration" />
                </ListItem>
            </RouteLink>
        </Fragment>
    )

    const AuthLinks = (props) => {
        return (<Fragment>
                    <ListItem button>
                        <ListItemIcon>
                            <AccountCircleRoundedIcon/>
                        </ListItemIcon>
                        <ListItemText primary={'Hello '+(state.user == null ? 'user' : state.user)} />
                    </ListItem>
                    { state.roles.indexOf('superadministrator') != -1 ? <AdminBar/> : null }
                    { state.roles.indexOf('user') != -1 ? <UserBar/> : null }
                    <ListItem button onClick={handleClick}>
                        <ListItemIcon>
                            <ExitToAppIcon/>
                        </ListItemIcon>
                        <ListItemText primary="Logout" />
                    </ListItem>
                </Fragment>);
    }

    return (
        <List>
            <RouteLink to={route('welcome')}>
                <ListItem button>
                    <ListItemIcon>
                        <HomeOutlinedIcon />
                    </ListItemIcon>
                    <ListItemText primary="Home" />
                </ListItem>
            </RouteLink>
            {state.isAuthenticated ? <AuthLinks/> : guestLinks}
          {/* {['Login', 'Starred', 'Send email', 'Drafts'].map((text, index) => (
            <ListItem button key={text}>
              <ListItemIcon>{index % 2 === 0 ? <InboxIcon/> : <MailIcon />}</ListItemIcon>
              <ListItemText primary={text} />
            </ListItem>
          ))} */}
        </List>
    );
}
