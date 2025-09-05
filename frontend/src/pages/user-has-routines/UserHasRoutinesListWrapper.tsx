import { Fragment } from "react";
import { FiltersProvider } from "../../components/providers/FiltersProvider";
import UserHasRoutinesList from "./routines-list/UserHasRoutinesList";

export const UserHasRoutinesListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <UserHasRoutinesList />
            </FiltersProvider>
        </Fragment>
    )
}

export default UserHasRoutinesListWrapper;