import { Fragment } from "react";
import { FiltersProvider } from "../../components/providers/FiltersProvider";
import RoutinesList from "./routines-list/RoutinesList";

export const RoutinesListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <RoutinesList />
            </FiltersProvider>
        </Fragment>
    )
}

export default RoutinesListWrapper;