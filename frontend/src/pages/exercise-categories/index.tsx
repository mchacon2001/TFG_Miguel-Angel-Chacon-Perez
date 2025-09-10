import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const ExerciseCategoriesWrapper = () => {

    return (
        <Fragment>
            <Outlet />
        </Fragment>
    )
}

export default ExerciseCategoriesWrapper;