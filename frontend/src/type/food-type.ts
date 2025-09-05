import { DietService } from "../services/diets/dietService";
import { ApiResponse, CreatedAt, Filters, Permission, Role, UpdatedAt } from "./apiResponse-type";
import { User } from "./user-type";


export type Foods = Food[] | null;
export type FoodError = Error | null;

export interface FoodApiResponse extends ApiResponse {
    totalRegisters: number;
    food: Food;
    lastPage: number;
    filters: Filters;
}

export interface FoodApiResponse extends ApiResponse {
    data: Food | null;
}

export interface Food {
    id: string;
    name: string;
    description: string;
    calories: number;
    protein: number;
    carbs: number;
    fat: number;
    user: User | null;

}
export interface RoutineHasFood {
    dayOfweek: string;
    food: Food;
    mealType: string;
    amount: number;
    notes: string;
    createdAt: CreatedAt;
    updatedAt: UpdatedAt | null;
  }


export interface NewFood {

}

export type CreateFoodFieldsModel = {
  [key: string]: string | number | boolean | File | null | undefined;
  foodId?: string | null;
  name: string;
  description?: string;
  calories: number;
  proteins: number;
  carbs: number;
  fats: number;
};

export type EditFoodFieldsModel = CreateFoodFieldsModel;