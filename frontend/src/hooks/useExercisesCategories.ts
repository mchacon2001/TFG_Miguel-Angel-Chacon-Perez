import { useCallback, useEffect, useState } from 'react';
import { ExerciseService } from '../services/exercises/exerciseService';
import { userIsSuperAdmin } from '../utils/userIsSuperAdmin';
import useFilters from './useFilters';
import useHandleErrors from './useHandleErrors';

//------------------------------------------------------------------------
/**
* 
* EN: Hook to manage exercises categories fetching and transformation logic.
* ES: Hook para gestionar la lógica de obtención y transformación de categorias de ejercicios.
*
*/
//----------------------------------------------------------------------
const useExercisesCategories = () => {

  const { handleErrors } = useHandleErrors();
  const { filters } = useFilters({}, [], 1, 1000);

  const [exercisesCategories, setExercisesCategories] = useState<any>([]);

  const fetchExercisesCategories = useCallback(async () => {
    try {
      const response = await (new ExerciseService()).getExercisesCategories(filters);
      const fetchedData = response.getResponseData() as any;

      if (fetchedData && fetchedData.data.exerciseCategories) {
        const mappedData = fetchedData.data.exerciseCategories.map((entity: { id: string; name: string;}) => ({
          value: entity.id,
          label: entity.name,
          key: entity.id,
        }));
        setExercisesCategories(mappedData);
      } else {
        handleErrors(response);
      }

    } catch (error) {
      console.log('Error fetching roles:', error);
    }
  }, [filters]);

  useEffect(() => {
    fetchExercisesCategories();
  }, [])

  const getExercisesCategoriesList = () => {
    return exercisesCategories;
  }

  return { exercisesCategories, fetchExercisesCategories, getExercisesCategoriesList };
}

export default useExercisesCategories;