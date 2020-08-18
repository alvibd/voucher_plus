import React, { useContext, Fragment } from 'react'

import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import AccountCircleRoundedIcon from '@material-ui/icons/AccountCircleRounded';

import { AuthContext } from '../context/AuthContext';

export default function AdminBar() {
    const {state, dispatch} = useContext(AuthContext)
    return (
        <Fragment>
            <ListItem button>
                <ListItemIcon>
                    <AccountCircleRoundedIcon/>
                </ListItemIcon>
                <ListItemText primary={'Hello '+(state.user == null ? 'user' : state.user)} />
            </ListItem>
        </Fragment>
    )
}
