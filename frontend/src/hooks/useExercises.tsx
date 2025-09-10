import { useCallback } from "react";
import { ExerciseService } from "../services/exercises/exerciseService";
import { ExercisesApiResponse } from "../type/exercise-type";
import useFetch from "./useFetch";
import useFilters from "./useFilters";

const useExercises = () => {

    const { filters, updatePageSize } = useFilters({}, [], 1, 9999999);

    const [exercises] = useFetch(useCallback(async () => {
        const response = await (new ExerciseService()).getExercises(filters);
        return response.getResponseData() as ExercisesApiResponse;
    }, [filters]));

    const fetchEntity = useCallback(async () => {
        const response = await (new ExerciseService()).getExercises(filters);
        const responseData = response.getResponseData();

        const exercisesNames = responseData?.data?.exercises?.map((exercise: any) => ({
            value: exercise.id,
            label: exercise.name,
        }));

        return exercisesNames;
    }, [filters]);

    const [exercisesListed] = useFetch(fetchEntity);

    const updateFilterLimit = (newLimit: number) => {
        updatePageSize({ value: newLimit });
    };

    const getExercises = () => {
        const exercisesNames: any = [];
        exercises?.exercises.map((exercise: any) => {
            exercisesNames.push({ value: exercise.id, label: exercise.name });
        });
        return exercisesNames;
    };

    const getExercisesLimited = (limit?: number) => {
        updatePageSize({ value: limit || 50 });
        return exercisesListed;
    };

    return { exercises, getExercises, getExercisesLimited, updateFilterLimit };
}

export default useExercises;