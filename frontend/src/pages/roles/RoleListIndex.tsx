import { Fragment } from "react"
import { FiltersProvider } from "../../components/providers/FiltersProvider"
import RoleList from "./role-list/RoleList"

const RoleListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <RoleList />
            </FiltersProvider>
        </Fragment>
    )
}

export default RoleListWrapper;