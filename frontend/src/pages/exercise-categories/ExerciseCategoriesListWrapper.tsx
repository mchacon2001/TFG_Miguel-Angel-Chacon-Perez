import { Fragment } from "react";
import { FiltersProvider } from "../../components/providers/FiltersProvider";
import ExerciseCategoriesList from "./category-list/ExerciseCategoriesList";

export const ExerciseCategoriesListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <ExerciseCategoriesList />
            </FiltersProvider>
        </Fragment>
    )
}