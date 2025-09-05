import { useCallback, useEffect, useState } from 'react';
import { RoutineService } from '../services/routines/routineService';

//------------------------------------------------------------------------
/**
* EN: Hook to manage routines categories fetching and transformation logic.
* ES: Hook para gestionar la lógica de obtención y transformación de categorías de rutinas.
*/
//----------------------------------------------------------------------
const useRoutinesCategories = () => {
    const [routinesCategories, setRoutinesCategories] = useState<any>([]);

    const fetchRoutinesCategories = useCallback(async () => {
        try {
            const response = await new RoutineService().getRoutineCategories();
            const fetchedData = response.getResponseData() as any;

            if (fetchedData && fetchedData.data.routines) {
                const mappedData = fetchedData.data.routines.map((entity: { id: string; name: string; }) => ({
                    value: entity.id,
                    label: entity.name,
                    key: entity.id
                }));
                setRoutinesCategories(mappedData);
            }
        } catch (error) {
            console.log('Error fetching categories:', error);
        }
    }, []);

    useEffect(() => {
        fetchRoutinesCategories();
    }, []);

    const getRoutinesCategoriesList = () => {
        return routinesCategories;
    };

    return { routinesCategories, fetchRoutinesCategories, getRoutinesCategoriesList };
};

export default useRoutinesCategories;
