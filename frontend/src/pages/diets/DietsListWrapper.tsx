import { Fragment } from "react";
import { FiltersProvider } from "../../components/providers/FiltersProvider";
import DietList from "./diets-list/DietsList";

export const DietsListWrapper = () => {
    return (
        <Fragment>
            <FiltersProvider>
                <DietList />
            </FiltersProvider>
        </Fragment>
    )
}

export default DietsListWrapper;