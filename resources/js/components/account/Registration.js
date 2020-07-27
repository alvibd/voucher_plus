import React, { useState, useContext } from 'react';
import { Redirect } from 'react-router-dom';

import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CssBaseline from '@material-ui/core/CssBaseline';
import TextField from '@material-ui/core/TextField';
import Link from '@material-ui/core/Link';
import Paper from '@material-ui/core/Paper';
import Box from '@material-ui/core/Box';
import Grid from '@material-ui/core/Grid';
import LockOutlinedIcon from '@material-ui/icons/LockOutlined';
import Typography from '@material-ui/core/Typography';
import { makeStyles } from '@material-ui/core/styles';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import FormHelperText from '@material-ui/core/FormHelperText';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';

import { useSnackbar } from 'notistack';
import { AuthContext } from '../context/AuthContext'

import {registration} from '../actions/auth'

function Copyright() {
  return (
    <Typography variant="body2" color="textSecondary" align="center">
      {'Copyright © '}
      <Link color="inherit" href="https://material-ui.com/">
        Your Website
      </Link>{' '}
      {new Date().getFullYear()}
      {'.'}
    </Typography>
  );
}

const useStyles = makeStyles((theme) => ({
  root: {
    height: '100vh',
  },
  image: {
    backgroundImage: 'url(https://source.unsplash.com/random)',
    backgroundRepeat: 'no-repeat',
    backgroundColor:
      theme.palette.type === 'light' ? theme.palette.grey[50] : theme.palette.grey[900],
    backgroundSize: 'cover',
    backgroundPosition: 'center',
  },
  paper: {
    margin: theme.spacing(8, 4),
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
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
    minWidth: 120,
  },
  selectEmpty: {
    marginTop: theme.spacing(2),
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

export default function Registration() {
    const classes = useStyles();
    const {enqueueSnackbar} = useSnackbar()

    const {dispatch, state} = useContext(AuthContext)

    const initialState = {
        name: "",
        email: "",
        password: "",
        password_confirmation : "",
        sex: '',
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

    const [data, setdata] = useState(initialState)

    const onChange = (e) => {
        setdata({
            ...data,
            [e.target.name]: e.target.value,
            [e.target.name+'HelperText']: '',
            [e.target.name+'Error']: false,
        });
    }

    const validate = () => {
      //validate email
      let error = false

      if(data.name == '' || data.name == null){
        error = true
        setdata({
          ...data,
          nameHelperText: 'Name field cannot be empty',
          nameError: true,
        })
      }

      let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      if(!re.test(data.email)){
        error = true
        setdata({
          ...data,
          emailHelperText: 'Given email is not a valid email address.',
          emailError: true,
        })
      }
      if(data.password != data.password_confirmation)
      {
        error = true
        setdata({
          ...data,
          passwordError: true,
          passwordHelperText: 'Password and confirmation password does not match.'
        })
      }
      else if(data.password_confirmation.length < 8)
      {
        error = true
        setdata({
          ...data,
          passwordError: true,
          passwordHelperText: 'Password must be at least 8 characters.'
        })
      }
      if(['male', 'female', 'other'].indexOf(data.sex) == -1 || data.sex == "")
      {
        error = true
        setdata({
          ...data,
          sexError: true,
          sexHelperText: 'Select a sex.'
        })
      }

      return error;
    }

    const onSelect = (e) => {
        setdata({
            ...data,
            sex: e.target.value,
            sexHelperText: '',
            sexError: false,
        });
    }

    const onSubmit = (e) => {
      e.preventDefault();
      if(validate()) return

      registration(dispatch, data.name, data.email, data.password, data.password_confirmation, data.sex).then(() => {
          enqueueSnackbar("Registration Successful", {
              variant : 'success',
              persist: true
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

  // const context = useContext(AuthContext)
  if (state.isAuthenticated) {
      return <Redirect to="/home" />;
  }

  return (
    <Grid container component="main" className={classes.root}>
      <CssBaseline />
      <Grid item xs={false} sm={4} md={7} className={classes.image} />
      <Grid item xs={12} sm={8} md={5} component={Paper} elevation={6} square>
        <div className={classes.paper}>
          <Avatar className={classes.avatar}>
            <LockOutlinedIcon />
          </Avatar>
          <Typography color='primary' component="h1" variant="h5">
            Register
          </Typography>
          <form className={classes.form} onSubmit={onSubmit}>
          <TextField
              variant="outlined"
              margin="normal"
              required
              fullWidth
              id="name"
              label="Full Name"
              name="name"
              autoComplete="John Doe"
              onChange={onChange}
              autoFocus
              helperText={data.nameHelperText}
              error={data.nameError}
            />
            <TextField
              variant="outlined"
              margin="normal"
              required
              fullWidth
              id="email"
              label="Email Address"
              name="email"
              autoComplete="email"
              onChange={onChange}
              autoFocus
              helperText={data.emailHelperText}
              error={data.emailError}
            />
            <TextField
              variant="outlined"
              margin="normal"
              required
              fullWidth
              name="password"
              label="Password"
              type="password"
              id="password"
              autoComplete="current-password"
              onChange={onChange}
              helperText={data.passwordHelperText}
              error={data.passwordError}
            />
            <TextField
              variant="outlined"
              margin="normal"
              required
              fullWidth
              name="password_confirmation"
              label="password confirmation"
              type="password"
              id="password_confirmation"
              autoComplete="Re-type your password"
              onChange={onChange}
              helperText={data.passwordHelperText}
              error={data.passwordError}
            />
            <FormControl variant="outlined" required className={classes.formControl} error={data.sexError}>
                <InputLabel id="demo-simple-select-outlined-label">Sex</InputLabel>
                <Select
                  labelId="demo-simple-select-outlined-label"
                  id="demo-simple-select-outlined"
                  value={data.sex}
                  onChange={onSelect}
                  label="sex"
                >
                    <MenuItem value="" selected>
                        <em>None</em>
                    </MenuItem>
                    <MenuItem value={'male'}>Male</MenuItem>
                    <MenuItem value={'female'}>Female</MenuItem>
                    <MenuItem value={'other'}>Other</MenuItem>
                </Select>
                <FormHelperText>{data.sexHelperText}</FormHelperText>
            </FormControl>
            <Button
              type="submit"
              fullWidth
              variant="contained"
              color="primary"
              className={classes.submit}
            >
              Register
            </Button>
            <Grid container>
              <Grid item xs>
                <Link href="#" variant="body2">
                  Forgot password?
                </Link>
              </Grid>
              <Grid item>
                <Link href="/login" variant="body2">
                  {"Already have an account? Sign In"}
                </Link>
              </Grid>
            </Grid>
            <Box mt={5}>
              <Copyright />
            </Box>
          </form>
        </div>
      </Grid>
    </Grid>
  );
}
