import React, { FC, Fragment, useState } from 'react'
import Button from '../../../../components/bootstrap/Button'
import FormGroup from '../../../../components/bootstrap/forms/FormGroup'
import OffCanvas, { OffCanvasBody, OffCanvasHeader, OffCanvasTitle } from '../../../../components/bootstrap/OffCanvas'
import Icon from '../../../../components/icon/Icon'
import Select from '../../../../components/bootstrap/forms/Select'
import CustomSearchInput from "../../../../components/bootstrap/forms/CustomSearchInput";
import { FilterOptions } from '../../../../hooks/useFilters'
import Input from '../../../../components/bootstrap/forms/Input'
import useExercisesCategories from '../../../../hooks/useExercisesCategories'
import { userIsSuperAdmin } from '../../../../utils/userIsSuperAdmin'

interface IExerciseFiltersProps {
  updateFilters: (filters: any) => void
  resetFilters: (limit: any) => void
  filters: FilterOptions
}

const ExerciseFilters: FC<IExerciseFiltersProps> = ({ updateFilters, filters, resetFilters }) => {

  const [filterMenu, setFilterMenu] = useState(false)

  const { getExercisesCategoriesList } = useExercisesCategories();

  const handleTextChange = (search: string) => {
    updateFilters({ search_array: search });
  };

  return (
    <Fragment>
      <label className='border-0 bg-transparent cursor-pointer' htmlFor='searchInput'>
        <Icon icon='Search' size='2x' color='primary' />
      </label>
      <CustomSearchInput placeholder={'Buscar'} onSearch={handleTextChange} defaultValue={filters.filter_filters?.search_text || ''}></CustomSearchInput>

      <Button color='primary' isLight icon='FilterAlt' onClick={() => {
        setFilterMenu(true)
      }}>
        Filtros
      </Button>

      <OffCanvas setOpen={setFilterMenu} isOpen={filterMenu} titleId='userFilters' isBodyScroll placement='end'>
        <OffCanvasHeader setOpen={setFilterMenu}>
          <OffCanvasTitle id='userFilters'> Filtros de Ejercicios </OffCanvasTitle>
        </OffCanvasHeader>
        <OffCanvasBody>
          <div className='row g-4'>
            <div className='col-12'>
              <FormGroup label='Categoría:'>
                <Select id='exercise_category' onChange={(e: React.ChangeEvent<HTMLInputElement>) => updateFilters({ exercise_category: e.target.value })} value={filters.filter_filters?.exercise_category || ''} ariaLabel='Default select example' placeholder='Elegir categoria...' list={getExercisesCategoriesList()}
                />
              </FormGroup>
            </div>
          </div>
          <div className='col-12 d-flex justify-content-center'>
            <Button
              className='mt-4'
              color="storybook"
              isLight
              onClick={() => resetFilters(50)}
            >
              Resetear
            </Button>
          </div>
        </OffCanvasBody>
      </OffCanvas>
    </Fragment>
  )
}

export default ExerciseFilters