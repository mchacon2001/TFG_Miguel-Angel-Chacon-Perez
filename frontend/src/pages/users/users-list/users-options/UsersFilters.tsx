import React, { FC, Fragment, useState } from 'react'
import Button from '../../../../components/bootstrap/Button'
import FormGroup from '../../../../components/bootstrap/forms/FormGroup'
import OffCanvas, { OffCanvasBody, OffCanvasHeader, OffCanvasTitle } from '../../../../components/bootstrap/OffCanvas'
import Icon from '../../../../components/icon/Icon'
import Select from '../../../../components/bootstrap/forms/Select'
import CustomSearchInput from "../../../../components/bootstrap/forms/CustomSearchInput";
import { DateRange } from 'react-date-range';
import es from 'date-fns/locale/es';

interface IUsersFiltersProps {
  updateFilters: (filters: any) => void
  resetFilters: (limit: any) => void
  filters: any
}

const UsersFilters: FC<IUsersFiltersProps> = ({ updateFilters, filters, resetFilters }) => {

  const [filterMenu, setFilterMenu] = useState(false);

  const handleTextChange = (search: string) => {
    updateFilters({ search_array: search });
  };

  return (
    <Fragment>
      <label className='border-0 bg-transparent cursor-pointer' htmlFor='searchInput'>
        <Icon icon='Search' size='2x' color='primary' />
      </label>
      <CustomSearchInput placeholder={'Buscar'} onSearch={handleTextChange} defaultValue={filters.filter_filters?.search_text || ''}></CustomSearchInput>

      <Button color='primary' isLight icon='FilterAlt' onClick={() => { setFilterMenu(true) }}>
        Filtros
      </Button>

      <OffCanvas setOpen={setFilterMenu} isOpen={filterMenu} titleId='userFilters' isBodyScroll placement='end'>
        <OffCanvasHeader setOpen={setFilterMenu}>
          <OffCanvasTitle id='userFilters'> Filtros de Usuario </OffCanvasTitle>
        </OffCanvasHeader>
        <OffCanvasBody>
          <div className='row g-4'>      
              <FormGroup id='filter2' label='Fecha de creación' className='col-12'>
                <DateRange
                  locale={es}
                  rangeColors={['#000']}
                  editableDateInputs={true}
                  onChange={item => {
                    const startDateFormatted = item.selection.startDate ? item.selection.startDate.toLocaleDateString('en-CA') : null;
                    const endDateFormatted = item.selection.endDate ? item.selection.endDate.toLocaleDateString('en-CA') : null;

                    updateFilters({
                      between_dates: {
                        startDate: startDateFormatted,
                        endDate: endDateFormatted
                      }
                    });
                  }}
                  moveRangeOnFirstSelection={false}
                  ranges={[
                    {
                      startDate: filters.filter_filters?.between_dates?.startDate
                        ? new Date(filters.filter_filters.between_dates.startDate)
                        : undefined,
                      endDate: filters.filter_filters?.between_dates?.endDate
                        ? new Date(filters.filter_filters.between_dates.endDate)
                        : undefined,
                      key: 'selection'
                    }
                  ]} />
              </FormGroup>
           

            <div className='col-md-12'>
              <Button className='mt-4' color="storybook" isLight onClick={() => resetFilters(50)}>
                Resetear
              </Button>
            </div>
          </div>
        </OffCanvasBody>
      </OffCanvas>
    </Fragment>
  )
}

export default UsersFilters