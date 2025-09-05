import { ApiResponse, CreatedAt, Filters, UpdatedAt } from "./apiResponse-type";
import { Food } from "./food-type";

export type Diets = Diet[] | null;
export type DietError = Error | null;

export interface DietsApiResponse extends ApiResponse {
    totalRegisters: number;
    diets: Diets;
    lastPage: number;
    filters: Filters;
}

export interface DietApiResponse extends ApiResponse {
    data: Diets | null;
}

export interface Diet {
    dietId: string;
    name: string;
    description: string;
    goal: string;
    createdAt: string;
    updatedAt: string | null;
    dietHasFood: DietHasFood[];
}

export interface DietHasFood {
  id: string;
  dayOfWeek: string;
  mealType: string;
  amount: number;
  notes?: string;
  createdAt: CreatedAt;
  updatedAt?: UpdatedAt | null;
  food: Food;
}

export interface NewDiet {

}

export type CreateDietFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    dietId: string | null;
    name: string;
    description: string;
    goal: string;
    dietHasFood: DietHasFood[] | any;
}

export type EditDietFieldsModel = {
    [key: string]: string | number | boolean | File | null | undefined;
    dietId: string | null;
    name: string;
    description?: string;
    goal: string;
    dietFood: DietHasFood[] | any;
    // Add flag fields
    toGainMuscle?: boolean;
    toLoseWeight?: boolean;
    toMaintainWeight?: boolean;
}
