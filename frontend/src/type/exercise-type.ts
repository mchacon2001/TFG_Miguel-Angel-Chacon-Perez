import { ApiResponse, CreatedAt, Filters, Permission, Role, UpdatedAt } from "./apiResponse-type";
import { User } from "./user-type";

export type Exercises = Exercise[] | null;
export type ExerciseError = Error | null;

export interface ExercisesApiResponse extends ApiResponse {
    totalRegisters: number;
    exercises: Exercises;
    lastPage: number;
    filters: Filters;
}

export interface ExerciseCategoriesApiResponse extends ApiResponse {
    exerciseCategories: ExerciseCategory[] | null;
    lastPage: number;
    filters: Filters;
    totalRegisters: number;
}

export interface ExerciseApiResponse extends ApiResponse {
    data: Exercise | null;
}

export interface Exercise {
    id: string;
    name: string;
    createdAt: CreatedAt;
    updatedAt: UpdatedAt | null;
    active: boolean;
    exerciseCategories: any | null;
    user: User | null;

}
export interface RoutineHasExercise {
    day: number;
    exercise: Exercise;
    sets: number;
    reps: number;
    restTime: number;
  }

export interface ExerciseCategory {
    exerciseCategoryId: string;
    name: string;
    description: string;
}

export interface NewExercise {

}

export type CreateExerciseFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    exerciseId: string | null;
    exerciseCategoryId: string;
    name: string;
}

export type EditExerciseFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    exerciseId: string | null;
    exerciseCategoryId: string;
    name: string;
}

export type EditCategoryFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    exerciseCategoryId: string | null;
    name: string;
    description: string;
}