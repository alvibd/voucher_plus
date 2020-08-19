import axios from 'axios'

// Setup config with token - helper function
const tokenConfig = (state) => {
    // Get token from state
    const token = state.access_token;

    // Headers
    const config = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    // If token, add to headers config
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }

    return config;
};


export const registration = (dispatch, state, organization_name, contact_no, address, city, postal_code, tin_no, organization_type, category) => {
    // Headers
    const config = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    // Request Body
    const body = JSON.stringify({ organization_name, contact_no, address, city, postal_code, tin_no, organization_type, category });

    return axios
    .post('/api/vendor/registration', body,tokenConfig(state))
    .then((res) => {
        let token = res.headers.authorization.slice(7);
        let payload = res.data.data;
        // console.log(payload)


        dispatch({
            type: "REFRESH_TOKEN",
            token : token,
        });
    })
    .catch((err) => {
        let token = res.headers.authorization.slice(7);
        dispatch({
            type: "REFRESH_TOKEN",
            token : token,
        });
        
        throw err;
    })
};
