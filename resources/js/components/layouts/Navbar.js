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
import { Link } from '@material-ui/core';
import { useSnackbar } from 'notistack';

import { AuthContext } from '../context/AuthContext';
import {loadUser, logout} from '../actions/auth'


export default function Navbar() {
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
            <Link href="/login" underline="none">
                <ListItem button>
                    <ListItemIcon>
                        <Icon className="material-icons">login</Icon>
                    </ListItemIcon>
                    <ListItemText primary="Login" />
                </ListItem>
            </Link>
            <Link href="/register" underline="none">
                <ListItem button>
                    <ListItemIcon>
                        <Icon className="material-icons">account_box</Icon>
                    </ListItemIcon>
                    <ListItemText primary="Registration" />
                </ListItem>
            </Link>
        </Fragment>
    )

    const authLinks = (
        <Fragment>
                <ListItem button>
                    <ListItemIcon>
                        <AccountCircleRoundedIcon/>
                    </ListItemIcon>
                    <ListItemText primary={'Hello '+(state.user == null ? 'user' : state.user)} />
                </ListItem>
                <ListItem button onClick={handleClick}>
                    <ListItemIcon>
                        <ExitToAppIcon/>
                    </ListItemIcon>
                    <ListItemText primary="Logout" />
                </ListItem>
        </Fragment>
    )

    return (
        <List>
            <Link href='/' underline="none">
                <ListItem button>
                    <ListItemIcon>
                        <HomeOutlinedIcon />
                    </ListItemIcon>
                    <ListItemText primary="Home" />
                </ListItem>
            </Link>
            {state.isAuthenticated ? authLinks: guestLinks}
          {/* {['Login', 'Starred', 'Send email', 'Drafts'].map((text, index) => (
            <ListItem button key={text}>
              <ListItemIcon>{index % 2 === 0 ? <InboxIcon/> : <MailIcon />}</ListItemIcon>
              <ListItemText primary={text} />
            </ListItem>
          ))} */}
        </List>
    );
}
