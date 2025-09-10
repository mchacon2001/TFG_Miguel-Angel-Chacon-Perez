import { FC, Fragment } from 'react'
import Icon from '../../../../components/icon/Icon'
import CustomSearchInput from "../../../../components/bootstrap/forms/CustomSearchInput";
import { FilterOptions } from '../../../../hooks/useFilters';

interface ICategoryFiltersProps {
  updateFilters: (filters: any) => void
  resetFilters: (limit: any) => void
  filters: FilterOptions
}

const CategoryFilters: FC<ICategoryFiltersProps> = ({ updateFilters, filters, resetFilters }) => {

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

export default CategoryFilters;