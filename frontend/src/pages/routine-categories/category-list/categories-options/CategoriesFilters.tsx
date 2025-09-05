import { FC, Fragment, useCallback, useContext, useState } from 'react'
import Icon from '../../../../components/icon/Icon'
import CustomSearchInput from "../../../../components/bootstrap/forms/CustomSearchInput";
import { FilterOptions } from '../../../../hooks/useFilters';
import useFetch from '../../../../hooks/useFetch'
import { UserService } from '../../../../services/users/userService'
import { UserApiResponse } from '../../../../type/user-type'
import { PrivilegeContext } from '../../../../components/priviledge/PriviledgeProvider'

interface ICategoryFiltersProps {
  updateFilters: (filters: any) => void
  resetFilters: (limit: any) => void
  filters: FilterOptions
}

const CategoryFilters: FC<ICategoryFiltersProps> = ({ updateFilters, filters, resetFilters }) => {

  const { userCan } = useContext(PrivilegeContext);

  const [filterMenu, setFilterMenu] = useState(false);

  const [users] = useFetch(useCallback(async () => {
    const response = await (new UserService).getUsers();
    return response.getResponseData() as UserApiResponse;
  }, []));

  const getUserList = () => {
    if (users?.users) {
      return users.users.map((user: any) => {
        return {
          value: user.id,
          label: user.name + (user.firstName ? (" " + user.firstName) : ''),
        }
      })
    }
    return [];
  };

  const handleTextChange = (search: string) => {
    updateFilters({ search_array: search });
  };

  return (
    <Fragment>
      <label className='border-0 bg-transparent cursor-pointer' htmlFor='searchInput'>
        <Icon icon='Search' size='2x' color='primary' />
      </label>
      <CustomSearchInput placeholder={'Buscar'} onSearch={handleTextChange} defaultValue={filters.filter_filters?.search_text || ''}></CustomSearchInput>
    </Fragment>
  )
}

export default CategoryFilters;