import { Fragment } from "react";
import { FiltersProvider } from "../../components/providers/FiltersProvider";
import UserHasDietsList from "./diets-list/UserHasDietsList";

export const UserHasDietsListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <UserHasDietsList />
            </FiltersProvider>
        </Fragment>
    )
}

export default UserHasDietsListWrapper;