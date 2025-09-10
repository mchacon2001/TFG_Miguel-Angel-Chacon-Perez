import { useCallback, useEffect, useState } from 'react';
import { RoutineService } from '../services/routines/routineService';

//------------------------------------------------------------------------
/**
* EN: Hook to manage routine exercises fetching and transformation logic.
* ES: Hook para gestionar la lógica de obtención y transformación de los ejercicios de las rutinas.
*/
//------------------------------------------------------------------------

const useRoutineExercises = () => {
    const [routineExercises, setRoutineExercises] = useState<any>([]);

    const fetchRoutineExercises = useCallback(async () => {
        try {
            const response = await new RoutineService().listRoutineExercises();
            const fetchedData = response.getResponseData() as any;

            if (fetchedData?.data?.exercises) {
                const mappedData = fetchedData.data.exercises.map((entity: { id: string; name: string }) => ({
                    value: entity.id,
                    label: entity.name,
                    key: entity.id,
                }));
                setRoutineExercises(mappedData);
            }
        } catch (error) {
            console.log('Error fetching exercises:', error);
        }
    }, []);

    useEffect(() => {
        fetchRoutineExercises();
    }, []);

    const getRoutineExercisesList = () => routineExercises;

    return {
        routineExercises,
        fetchRoutineExercises,
        getRoutineExercisesList,
    };
};

export default useRoutineExercises;
