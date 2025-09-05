import { Fragment } from "react"
import { Outlet } from "react-router-dom"

const DietWrapper = () => {

    return (
        <Fragment>
           <Outlet/>
        </Fragment>
    )
}

export default DietWrapper;