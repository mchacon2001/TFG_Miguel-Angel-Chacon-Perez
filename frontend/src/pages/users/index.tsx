import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const UsersWrapper = () => {
    return (
        <Fragment>
            <Outlet />
        </Fragment>
    )
}

export default UsersWrapper;