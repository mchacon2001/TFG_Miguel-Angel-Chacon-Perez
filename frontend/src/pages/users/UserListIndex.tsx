import { Fragment } from "react"
import { FiltersProvider } from "../../components/providers/FiltersProvider"
import UsersList from "./users-list/UsersList"

const UserListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <UsersList />
            </FiltersProvider>
        </Fragment>
    )
}

export default UserListWrapper;