import { Fragment } from "react"
import { FiltersProvider } from "../../components/providers/FiltersProvider"
import FoodsList from "./food-list/FoodsList"

const FoodsListWrapper = () => {

    return (
        <Fragment>
            <FiltersProvider>
                <FoodsList />
            </FiltersProvider>
        </Fragment>
    )
}

export default FoodsListWrapper;