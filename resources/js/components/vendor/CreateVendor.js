import React from 'react'
import { Redirect } from 'react-router-dom'
import { useState, useEffect, useContext } from 'react';
import axios from 'axios'

import Button from '@material-ui/core/Button';
import TextField from '@material-ui/core/TextField';
import Typography from '@material-ui/core/Typography';
import Container from '@material-ui/core/Container';
import { makeStyles } from '@material-ui/core/styles';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import FormHelperText from '@material-ui/core/FormHelperText';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';

import { useSnackbar } from 'notistack';
import { AuthContext } from '../context/AuthContext'

import { registration } from '../actions/vendor'
import {loadUser} from '../actions/auth'
import {route} from '../common/Routes'

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

    const {enqueueSnackbar} = useSnackbar()

    const {dispatch, state} = useContext(AuthContext);

    const initialState = {
        organization_name: "",
        contact_no: "",
        address: "",
        city : "",
        postal_code: '',
        tin_no: '',
        organization_type: 'limited liability company',
        cateogries: [],
        category: '',
        success: false,
        // errors helpers
        // nameHelperText: '',
        // nameError: false,
        // emailHelperText: '',
        // emailError: false,
        // passwordHelperText: '',
        // passwordError: false,
        // sexHelperText: '',
        // sexError: false,
    }

    const [data, setdata]= useState(initialState)

    useEffect(() => {
        axios.get('/api/categories').then((res) => {
            console.log(res)
            setdata({
                ...data,
               cateogries: res.data.data
                // [e.target.name+'HelperText']: '',
                // [e.target.name+'Error']: false,
            });
        })
    },[])

    const onSubmit = (e) => {
        e.preventDefault();
        registration(dispatch, state, data.organization_name, data.contact_no, data.address, data.city, data.postal_code, data.tin_no, data.organization_type, data.category).then(() => {
            enqueueSnackbar("Vendor Profile Created", {
                variant : 'success',
                persist: true
            })

            //since role has beem updated
            // loadUser(dispatch, state)

            setdata({
                ...data,
                success: true,
            })

        }).catch((err) => {
          setdata({
            ...data,
            error: true
          })
          showError(err)
          // enqueueSnackbar(err.response.data.error, {
          //   variant : 'error',
          //   persist: true
          // })
        })
    }

    const showError = (err) => {
        let errors = []
            if(err.response.status == 422){
              let field = null
              for(field in err.response.data.errors){
                errors[field] = err.response.data.errors[field]
              }
            }

            let field = null
            for(field in errors)
            {
              let error= null
              for(error of errors[field]){
                enqueueSnackbar(error.replace('_', ' '), {
                  variant : 'error',
                  persist: true
                })
              }
            }
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

    if(data.success){
        return <Redirect to={route('home')} />;
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
                    <FormControl variant="filled" required className={classes.formControl}
                    // error={data.sexError}
                    >
                        <InputLabel id="demo-simple-select-outlined-label">Category</InputLabel>
                        <Select
                        native defaultValue="" id="grouped-native-select"
                        // value={data.sex}
                        onChange={onSelect}
                        label="category"
                        name="category"
                        >
                            <option aria-label="None" value="" />
                            {data.cateogries.map((category) =>
                                <optgroup key={category.id} label={category.name}>
                                    {/* {categoryMenuItems(category.children)} */}
                                    {category.children.map((subCategory) =>
                                        <option value={subCategory.id} key={subCategory.id}>{subCategory.name}</option>
                                    )}
                                </optgroup>
                            )}
                        </Select>
                        {/* <FormHelperText>{data.sexHelperText}</FormHelperText> */}
                    </FormControl>
                    <Button
                    type="submit"
                    fullWidth
                    variant="contained"
                    color="primary"
                    className={classes.submit}
                    >
                    Submit
                    </Button>
                </form>
             </div>
        </Container>
    )
}
