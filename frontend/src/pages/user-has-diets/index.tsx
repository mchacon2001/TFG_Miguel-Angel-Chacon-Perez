import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const UserHasDietsWrapper = () => {

    return (
        <Fragment>
           <Outlet/>
        </Fragment>
    )
}

export default UserHasDietsWrapper;