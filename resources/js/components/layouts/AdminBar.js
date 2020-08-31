import React, { useContext, Fragment } from 'react'
import {Link as RouteLink, Redirect } from 'react-router-dom'

import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import AccountCircleRoundedIcon from '@material-ui/icons/AccountCircleRounded';

import { AuthContext } from '../context/AuthContext';
import { route } from '../common/Routes';

export default function AdminBar() {
    const {state, dispatch} = useContext(AuthContext)
    return (
        <Fragment>
            <RouteLink to={route('vendor.list')}>
                <ListItem button>
                    <ListItemIcon>
                        <AccountCircleRoundedIcon/>
                    </ListItemIcon>
                    <ListItemText primary="Vendor List" />
                </ListItem>
            </RouteLink>
        </Fragment>
    )
}
