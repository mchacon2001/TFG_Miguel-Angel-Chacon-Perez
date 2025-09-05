import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const UserHasRoutinesWrapper = () => {

    return (
        <Fragment>
           <Outlet/>
        </Fragment>
    )
}

export default UserHasRoutinesWrapper;