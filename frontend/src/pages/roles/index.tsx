import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const RoleWrapper = () => {
    return (
        <Fragment>
            <Outlet />
        </Fragment>
    )
}

export default RoleWrapper;