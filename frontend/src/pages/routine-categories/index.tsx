import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const RoutineCategoriesWrapper = () => {

    return (
        <Fragment>
            <Outlet />
        </Fragment>
    )
}

export default RoutineCategoriesWrapper;