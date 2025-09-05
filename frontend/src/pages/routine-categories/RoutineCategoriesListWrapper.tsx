import { Fragment } from "react";
import { FiltersProvider } from "../../components/providers/FiltersProvider";
import RoutineCategoriesList from "./category-list/RoutineCategoriesList";

export const RoutineCategoriesListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <RoutineCategoriesList />
            </FiltersProvider>
        </Fragment>
    )
}

export default RoutineCategoriesListWrapper;