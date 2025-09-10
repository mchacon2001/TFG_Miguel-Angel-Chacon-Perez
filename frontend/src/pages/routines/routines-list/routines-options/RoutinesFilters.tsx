import { FC, Fragment, useState } from "react"
import { FilterOptions } from "../../../../hooks/useFilters"
import Button from "../../../../components/bootstrap/Button"
import CustomSearchInput from "../../../../components/bootstrap/forms/CustomSearchInput"
import Icon from "../../../../components/icon/Icon"
import OffCanvas, { OffCanvasHeader, OffCanvasTitle, OffCanvasBody } from "../../../../components/bootstrap/OffCanvas"
import FormGroup from "../../../../components/bootstrap/forms/FormGroup"
import { userIsSuperAdmin } from "../../../../utils/userIsSuperAdmin"
import Select from "../../../../components/bootstrap/forms/Select"
import Input from "../../../../components/bootstrap/forms/Input"
import useRoutinesCategories from "../../../../hooks/useRoutineCategories"
import useRoutineExercises from "../../../../hooks/useRoutineExercises"
import CustomSearchSelect from "../../../../components/customSearchSelect"
import FormLabel from "../../../../components/bootstrap/forms/FormLabel"

interface IExerciseFiltersProps {
    updateFilters: (filters: any) => void
    resetFilters: (limit: any) => void
    filters: FilterOptions
}

const RoutinesFilters: FC<IExerciseFiltersProps> = ({ updateFilters, filters, resetFilters }) => {

    const { getRoutinesCategoriesList } = useRoutinesCategories();
    const { getRoutineExercisesList } = useRoutineExercises();

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

            <Button color='primary' isLight icon='FilterAlt' onClick={() => { setFilterMenu(true) }}>
                Filtros
            </Button>

            <OffCanvas setOpen={setFilterMenu} isOpen={filterMenu} titleId='routineFilters'>
                <OffCanvasHeader setOpen={setFilterMenu}>
                    <OffCanvasTitle id='routineFilters'>Filtros de Rutinas</OffCanvasTitle>
                </OffCanvasHeader>
                <OffCanvasBody>
                    <div className="row g-4">
                        <FormGroup label='Categoría:' className="col-md-12">
                            <Select
                                id='routine_category'
                                list={getRoutinesCategoriesList()}
                                onChange={(e: React.ChangeEvent<HTMLInputElement>) => updateFilters({ routine_category: e.target.value })}
                                value={filters.filter_filters?.routine_category || ''}
                                placeholder='Elegir categoría ...'
                                ariaLabel='Default select example'
                            />
                        </FormGroup>

                        <FormGroup label="Ejercicios:" className="col-md-12">
                            <CustomSearchSelect
                                isMulti
                                isClearable
                                id='user-select'
                                options={getRoutineExercisesList()}
                                onChange={(e: any) => {
                                    const exercises = e.map((exercise: any) => {
                                        return { type: exercise.type, id: exercise.value }
                                    });
                                    updateFilters({ exercises: exercises });
                                }}
                                defaultValue={getRoutineExercisesList().find((option: any) => option.value === filters.filter_filters?.exercises)}
                            />
                        </FormGroup>

                
                        <div className='col-12 text-center' >
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

export default RoutinesFilters;