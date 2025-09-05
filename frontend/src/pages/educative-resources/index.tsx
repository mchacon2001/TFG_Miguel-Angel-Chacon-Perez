import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const EducativeResourceWrapper = () => {

    return (
        <Fragment>
            <Outlet />
        </Fragment>
    )
}

export default EducativeResourceWrapper;