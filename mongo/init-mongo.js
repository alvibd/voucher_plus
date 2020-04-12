db.createUser({
    user: 'rambo',
    pwd :'9l+-Upr@br4',
    roles: [
        {
            role : "readWrite",
            db: "voucher_plus"
        }
    ]
})
