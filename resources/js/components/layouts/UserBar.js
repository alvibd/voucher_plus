import React, { useContext, Fragment } from 'react'
import {Link as RouteLink, Redirect } from 'react-router-dom'

import Icon from '@material-ui/core/Icon';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import { Link } from '@material-ui/core';

import { AuthContext } from '../context/AuthContext';
import { route } from '../common/Routes';

export default function UserBar() {
    const {state, dispatch} = useContext(AuthContext)

    return (
        <Fragment>
            <RouteLink to={route('vendor.registration')}>
                <ListItem button>
                    <ListItemIcon>
                        <Icon className="material-icons">account_box</Icon>
                    </ListItemIcon>
                    <ListItemText primary="Create Vendor Profile" />
                </ListItem>
            </RouteLink>
        </Fragment>
    )
}
