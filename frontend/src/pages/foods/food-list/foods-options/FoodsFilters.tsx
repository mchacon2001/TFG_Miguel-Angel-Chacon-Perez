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

  const handleTextChange = (search: string) => {
    updateFilters({ search_array: search });
  };

  return (
    <Fragment>
      <label className='border-0 bg-transparent cursor-pointer' htmlFor='searchInput'>
        <Icon icon='Search' size='2x' color='primary' />
      </label>
      <CustomSearchInput
        placeholder={'Buscar'}
        onSearch={handleTextChange}
        defaultValue={filters.filter_filters?.search_text || ''}
      />

      <Button color='primary' isLight icon='FilterAlt' onClick={() => setFilterMenu(true)}>
        Filtros
      </Button>

      <OffCanvas setOpen={setFilterMenu} isOpen={filterMenu} titleId='userFilters' isBodyScroll placement='end'>
        <OffCanvasHeader setOpen={setFilterMenu}>
          <OffCanvasTitle id='userFilters'>Filtros de Alimentos</OffCanvasTitle>
        </OffCanvasHeader>
        <OffCanvasBody>
          <div className='row g-4'>

            <div className='col-12'>
              <FormGroup label='Rango de calorías (kcal):'>
                <div className='row g-2'>
                  <div className='col'>
                    <Input
                      type='number'
                      id='calories_min'
                      placeholder='Mín.'
                      value={filters.filter_filters?.calories_min || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ calories_min: e.target.value })
                      }
                    />
                  </div>
                  <div className='col'>
                    <Input
                      type='number'
                      id='calories_max'
                      placeholder='Máx.'
                      value={filters.filter_filters?.calories_max || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ calories_max: e.target.value })
                      }
                    />
                  </div>
                </div>
              </FormGroup>
            </div>

            <div className='col-12'>
              <FormGroup label='Rango de proteínas (g):'>
                <div className='row g-2'>
                  <div className='col'>
                    <Input
                      type='number'
                      id='proteins_min'
                      placeholder='Mín.'
                      value={filters.filter_filters?.proteins_min || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ proteins_min: e.target.value })
                      }
                    />
                  </div>
                  <div className='col'>
                    <Input
                      type='number'
                      id='proteins_max'
                      placeholder='Máx.'
                      value={filters.filter_filters?.proteins_max || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ proteins_max: e.target.value })
                      }
                    />
                  </div>
                </div>
              </FormGroup>
            </div>

            <div className='col-12'>
              <FormGroup label='Rango de grasas (g):'>
                <div className='row g-2'>
                  <div className='col'>
                    <Input
                      type='number'
                      id='fats_min'
                      placeholder='Mín.'
                      value={filters.filter_filters?.fats_min || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ fats_min: e.target.value })
                      }
                    />
                  </div>
                  <div className='col'>
                    <Input
                      type='number'
                      id='fats_max'
                      placeholder='Máx.'
                      value={filters.filter_filters?.fats_max || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ fats_max: e.target.value })
                      }
                    />
                  </div>
                </div>
              </FormGroup>
            </div>

            <div className='col-12'>
              <FormGroup label='Rango de carbohidratos (g):'>
                <div className='row g-2'>
                  <div className='col'>
                    <Input
                      type='number'
                      id='carbs_min'
                      placeholder='Mín.'
                      value={filters.filter_filters?.carbs_min || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ carbs_min: e.target.value })
                      }
                    />
                  </div>
                  <div className='col'>
                    <Input
                      type='number'
                      id='carbs_max'
                      placeholder='Máx.'
                      value={filters.filter_filters?.carbs_max || ''}
                      onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        updateFilters({ carbs_max: e.target.value })
                      }
                    />
                  </div>
                </div>
              </FormGroup>
            </div>
          </div>

          <div className='col-12 d-flex justify-content-center'>
            <Button
              className='mt-4'
              color='storybook'
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