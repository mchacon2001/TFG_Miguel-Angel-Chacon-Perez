import { Fragment } from "react"
import { FiltersProvider } from "../../components/providers/FiltersProvider"
import ExercisesList from "./exercise-list/ExercisesList"

const ExerciseListWrapper = () => {

    return (
        <Fragment>
            <FiltersProvider>
                <ExercisesList />
            </FiltersProvider>
        </Fragment>
    )
}

export default ExerciseListWrapper;