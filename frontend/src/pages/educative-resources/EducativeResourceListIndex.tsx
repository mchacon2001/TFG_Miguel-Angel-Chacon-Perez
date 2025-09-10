import { Fragment } from "react"
import { FiltersProvider } from "../../components/providers/FiltersProvider"
import EducativeResourceList from "./educative-list/EducativeResourceList"

const EducativeResourceListWrapper = () => {

    return (
        <Fragment>
            <FiltersProvider>
                <EducativeResourceList />
            </FiltersProvider>
        </Fragment>
    )
}

export default EducativeResourceListWrapper;