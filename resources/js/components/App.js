import React, { useContext } from 'react'
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route, Switch, Redirect } from 'react-router-dom';

import clsx from 'clsx';
import { makeStyles, createMuiTheme, ThemeProvider  } from '@material-ui/core/styles';
import Drawer from '@material-ui/core/Drawer';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import CssBaseline from '@material-ui/core/CssBaseline';
import Typography from '@material-ui/core/Typography';
import Divider from '@material-ui/core/Divider';
import IconButton from '@material-ui/core/IconButton';
import MenuIcon from '@material-ui/icons/Menu';
import ChevronLeftIcon from '@material-ui/icons/ChevronLeft';
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import Button from '@material-ui/core/Button';
import { SnackbarProvider } from 'notistack';

import { AuthProvider } from './context/AuthContext'
import PrivateRoute from './common/PrivateRoute'
import Navbar from './layouts/Navbar'
import Welcome from './Welcome'
import Home from './Home'
import Login from './account/Login'
import Registration from './account/Registration'
import CreateVendor from './vendor/CreateVendor';
import { route } from './common/Routes';

const drawerWidth = 240;

const useStyles = makeStyles((theme) => ({
    root: {
        display: 'flex',
    },
    appBar: {
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.sharp,
            duration: theme.transitions.duration.leavingScreen,
        }),
        background: 'linear-gradient(45deg, #2196F3 30%, #21CBF3 90%)',
    },
    appBarShift: {
        width: `calc(100% - ${drawerWidth}px)`,
        transition: theme.transitions.create(['margin', 'width'], {
            easing: theme.transitions.easing.easeOut,
            duration: theme.transitions.duration.enteringScreen,
        }),
        marginRight: drawerWidth,
    },
    title: {
        flexGrow: 1,
    },
    hide: {
        display: 'none',
    },
    drawer: {
        width: drawerWidth,
        flexShrink: 0,
    },
    drawerPaper: {
        width: drawerWidth,
    },
    drawerHeader: {
        display: 'flex',
        alignItems: 'center',
        padding: theme.spacing(0, 1),
        // necessary for content to be below app bar
        ...theme.mixins.toolbar,
        justifyContent: 'flex-start',
    },
    content: {
        flexGrow: 1,
        padding: theme.spacing(3),
        // transition: theme.transitions.create('margin', {
        //     easing: theme.transitions.easing.sharp,
        //     duration: theme.transitions.duration.leavingScreen,
        // }),
        marginRight: -drawerWidth,
    },
    // contentShift: {
    //     transition: theme.transitions.create('margin', {
    //         easing: theme.transitions.easing.easeOut,
    //         duration: theme.transitions.duration.enteringScreen,
    //     }),
    //     marginRight: 0,
    // },
}));


export default function App() {
    const classes = useStyles();
    const theme = createMuiTheme({palette: {type: 'dark'}});

    const [open, setOpen] = React.useState(false);
    const appName = process.env.MIX_APP_NAME

    const handleDrawerOpen = () => {
        setOpen(true);
    };

    const handleDrawerClose = () => {
        setOpen(false);
    };

    // add action to all snackbars
    const notistackRef = React.createRef();
    const onClickDismiss = key => () => {
        notistackRef.current.closeSnackbar(key);
    }

    return (
        <AuthProvider>
            <ThemeProvider theme={theme}>
                <SnackbarProvider
                maxSnack={3}
                anchorOrigin={{
                    vertical: 'bottom',
                    horizontal: 'center',
                }}
                autoHideDuration={6000}
                ref={notistackRef}
                action={(key) => (
                    <Button color="inherit" onClick={onClickDismiss(key)}>
                        {'Dismiss'}
                    </Button>
                )}
                >
                    <Router>
                    <div className={classes.root}>
                        <CssBaseline />
                        <AppBar
                        position="fixed"
                        className={clsx(classes.appBar, {
                            [classes.appBarShift]: open,
                        })}
                        >
                            <Toolbar>
                                <Typography variant="h6" noWrap className={classes.title}>
                                    {appName}
                                </Typography>
                                <IconButton
                                color="inherit"
                                aria-label="open drawer"
                                edge="end"
                                onClick={handleDrawerOpen}
                                className={clsx(open && classes.hide)}
                                >
                                    <MenuIcon />
                                </IconButton>
                            </Toolbar>
                        </AppBar>
                        <main
                        className={classes.content}
                        // className={clsx(classes.content, {
                        //     [classes.contentShift]: open,
                        // })}
                        >
                            <div className={classes.drawerHeader} />
                            <Switch>
                                <PrivateRoute exact path={route('home')} component={Home} />
                                <PrivateRoute exact path={route('vendor.registration')} component={CreateVendor} />
                                <Route exact path={route('welcome')} component={Welcome} />
                                <Route exact path={route('login')} component={Login} />
                                <Route exact path={route('registration')} component={Registration} />
                            </Switch>
                        </main>
                        <Drawer
                        className={classes.drawer}
                        variant="persistent"
                        anchor="right"
                        open={open}
                        classes={{
                            paper: classes.drawerPaper,
                        }}
                        >
                            <div className={classes.drawerHeader}>
                                <IconButton onClick={handleDrawerClose}>
                                {theme.direction === 'rtl' ? <ChevronLeftIcon /> : <ChevronRightIcon />}
                                </IconButton>
                            </div>
                            <Divider />
                            <Navbar/>

                        </Drawer>
                    </div>
                    </Router>
                </SnackbarProvider>
            </ThemeProvider>
        </AuthProvider>
        )
    }

ReactDOM.render(<App/>, document.getElementById('app'))
