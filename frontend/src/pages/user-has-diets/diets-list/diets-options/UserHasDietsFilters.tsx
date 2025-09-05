import { FC, Fragment, useState } from "react"
import { FilterOptions } from "../../../../hooks/useFilters"
import CustomSearchInput from "../../../../components/bootstrap/forms/CustomSearchInput"
import Icon from "../../../../components/icon/Icon"

interface IUserHasDietsFiltersProps {
    updateFilters: (filters: any) => void
    resetFilters: (limit: any) => void
    filters: FilterOptions
}

const UserHasDietsFilters: FC<IUserHasDietsFiltersProps> = ({ updateFilters, filters, resetFilters }) => {

    const [filterMenu, setFilterMenu] = useState(false);

    const handleTextChange = (search: string) => {
        updateFilters({ search_array: search });
    };

    return (
        <Fragment>
            <label className='border-0 bg-transparent cursor-pointer' htmlFor='searchInput'>
                <Icon icon='Search' size='2x' color='primary' />
            </label>
            <CustomSearchInput placeholder={'Buscar'} onSearch={handleTextChange} defaultValue={filters.filter_filters?.search_text || ''} />
        </Fragment>
    )
}

export default UserHasDietsFilters;