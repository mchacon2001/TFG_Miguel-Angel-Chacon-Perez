import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const ExercisesWrapper = () => {

    return (
        <Fragment>
            <Outlet />
        </Fragment>
    )
}

export default ExercisesWrapper;