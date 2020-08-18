import React from 'react'
import { useState, useEffect } from 'react';

import TextareaAutosize from '@material-ui/core/TextareaAutosize';
import TextField from '@material-ui/core/TextField';
import Typography from '@material-ui/core/Typography';
import Container from '@material-ui/core/Container';
import { makeStyles } from '@material-ui/core/styles';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import FormHelperText from '@material-ui/core/FormHelperText';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';

const useStyles = makeStyles((theme) => ({
    paper: {
        marginTop: theme.spacing(8),
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'left',
    },
    avatar: {
        margin: theme.spacing(1),
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing(1),
    },
    formControl: {
        margin: theme.spacing(1),
        minWidth: 300,
      },
    submit: {
        margin: theme.spacing(3, 0, 2),
        background: 'linear-gradient(45deg, #FE6B8B 30%, #FF8E53 90%)',
        border: 0,
        borderRadius: 3,
        boxShadow: '0 3px 5px 2px rgba(255, 105, 135, .3)',
        color: 'white',
        height: 48,
        padding: '0 30px',
    },
}));


export default function CreateVendor() {

    const classes = useStyles();
    const initialState = {
        organization_name: "",
        contact_no: "",
        address: "",
        city : "",
        postal_code: '',
        tin_no: '',
        organization_type: 'limited liability company',
        cateogries: [],
        // errors helpers
        nameHelperText: '',
        nameError: false,
        emailHelperText: '',
        emailError: false,
        passwordHelperText: '',
        passwordError: false,
        sexHelperText: '',
        sexError: false,
    }

    const [data, setdata]= useState(initialState)

    // useEffect(() => {
    //     effect
    //     return () => {
    //         cleanup
    //     }
    // }, [input])

    const onSubmit = (e) => {
        console.log(e.target)
    }

    const onChange = (e) => {
        setdata({
            ...data,
            [e.target.name]: e.target.value,
            // [e.target.name+'HelperText']: '',
            // [e.target.name+'Error']: false,
        });
    }
    const onSelect = (e) => {
        console.log(e.target)
        setdata({
            ...data,
            [e.target.name]: e.target.value,
        });
    }

    return (
        <Container maxWidth="xs">
             <div className={classes.paper}>
                <Typography color='primary' variant="h5">
                Create Vendor Profile
                </Typography>
                <form className={classes.form} onSubmit={onSubmit}>
                    <TextField
                    variant="filled"
                    margin="normal"
                    required
                    fullWidth
                    id="organization_name"
                    label="Organization Name"
                    name="organization_name"
                    autoComplete="Organization Name"
                    autoFocus
                    onChange={onChange}
                    // error={data.error}
                    // helperText={data.emailHelperText}
                    />
                    <TextField
                    variant="filled"
                    margin="normal"
                    required
                    fullWidth
                    id="contact_no"
                    label="Contact No"
                    name="contact_no"
                    autoComplete="Contact No"
                    onChange={onChange}
                    // error={data.error}
                    // helperText={data.emailHelperText}
                    />
                    <TextField
                    variant="filled"
                    margin="normal"
                    required
                    fullWidth
                    multiline
                    row={4}
                    id="address"
                    label="Address"
                    name="address"
                    autoComplete="Address"
                    onChange={onChange}
                    // error={data.error}
                    // helperText={data.emailHelperText}
                    />
                    <TextField
                    variant="filled"
                    margin="normal"
                    required
                    fullWidth
                    id="city"
                    label="city"
                    name="city"
                    autoComplete="city"
                    onChange={onChange}
                    // error={data.error}
                    // helperText={data.emailHelperText}
                    />
                    <TextField
                    variant="filled"
                    margin="normal"
                    required
                    fullWidth
                    id="postal_code"
                    label="Postal Code"
                    name="postal_code"
                    autoComplete="postal code"
                    onChange={onChange}
                    // error={data.error}
                    // helperText={data.emailHelperText}
                    />
                    <TextField
                    variant="filled"
                    margin="normal"
                    fullWidth
                    id="tin_no"
                    label="Tin No."
                    name="tin_no"
                    autoComplete="Tin No."
                    onChange={onChange}
                    // error={data.error}
                    // helperText={data.emailHelperText}
                    />
                    <FormControl variant="filled" required className={classes.formControl}
                    // error={data.sexError}
                    >
                        <InputLabel id="demo-simple-select-outlined-label">Organization Type</InputLabel>
                        <Select
                        labelId="demo-simple-select-outlined-label"
                        id="demo-simple-select-outlined"
                        // value={data.sex}
                        onChange={onSelect}
                        label="organization_type"
                        name="organization_type"
                        >
                            <MenuItem value={'sole proprietorship'}>Sole Proprietorship</MenuItem>
                            <MenuItem value={'partnership'}>Partnership</MenuItem>
                            <MenuItem value={'corporation'}>Corporation</MenuItem>
                            <MenuItem value={'limited liability company'} selected>Limited Liability Company</MenuItem>
                        </Select>
                        {/* <FormHelperText>{data.sexHelperText}</FormHelperText> */}
                    </FormControl>
                </form>
             </div>
        </Container>
    )
}
