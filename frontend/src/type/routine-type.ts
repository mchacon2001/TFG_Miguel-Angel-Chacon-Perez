import { ApiResponse, Filters } from "./apiResponse-type";

export type Routines = Routine[] | null;
export type RoutineError = Error | null;

export interface RoutinesApiResponse extends ApiResponse {
    totalRegisters: number;
    routines: Routines;
    lastPage: number;
    filters: Filters;
}

export interface RoutineApiResponse extends ApiResponse {
    data: Routines | null;
}

export interface Routine {
    routineId: string;
    routineCategoryId: string;
    name: string;
    description: string;
    routineExercises: RoutineExercise[];
}

export interface RoutineCategory {
    routineCategoryId: string;
    name: string;
    description: string;
}

export interface RoutineExercise {
    id: string;
    typeId: string;
    type: string;
}

export interface NewRoutine {

}

export type CreateRoutineFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    routineId: string | null;
    routineCategoryId: string;
    name: string;
    description: string;
    routineExercises: RoutineExercise[] | any;
    toGainMuscle?: boolean;
    toLoseWeight?: boolean;
    toMaintainWeight?: boolean;
    toImprovePhysicalHealth?: boolean;
    toImproveMentalHealth?: boolean;
    fixShoulder?: boolean;
    fixKnees?: boolean;
    fixBack?: boolean;
    rehab?: boolean;
}

export type EditRoutineFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    routineId: string | null;
    routineCategoryId: string;
    name: string;
    description: string;
    routineExercises: RoutineExercise[] | any;
    active?: boolean;
    toGainMuscle?: boolean;
    toLoseWeight?: boolean;
    toMaintainWeight?: boolean;
    toImprovePhysicalHealth?: boolean;
    toImproveMentalHealth?: boolean;
    fixShoulder?: boolean;
    fixKnees?: boolean;
    fixBack?: boolean;
    rehab?: boolean;
}

export type CreateCategoryFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    name: string;
    description: string;
}

export type EditCategoryFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    routineCategoryId: string | null;
    name: string;
    description: string;
}