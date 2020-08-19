export const route = (route_name, options= {}) => {
    let to;
    switch (route_name) {
        case 'welcome':
            to = '/'
            break;
        case 'home':
            to='/home';
            break;
        case 'login':
            to='/login';
            break;
        case 'registration':
            to='/registration';
            break;
        case 'vendor.registration':
            to='/vendor/registration'
            break;
        default:
            break;
    }

    return to;
}
