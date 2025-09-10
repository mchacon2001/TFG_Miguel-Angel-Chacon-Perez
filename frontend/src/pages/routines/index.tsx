import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const RoutinesWrapper = () => {

    return (
        <Fragment>
           <Outlet/>
        </Fragment>
    )
}

export default RoutinesWrapper;