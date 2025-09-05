import {
	superAdminMenu,
	usersMenu,
	permissionsPage,
	adminMenu,
	routinesMenu

} from '../menu';
import RoleWrapper from '../pages/roles';
import UsersWrapper from '../pages/users';
import UserEdit from '../pages/users/user-edit/UserEdit';
import UserProfile from '../pages/users/user-profile/UserProfile';
import RoleEditPermissions from '../pages/roles/role-edit/RoleEditPermissions';
import PagePermissions from '../pages/auth/PagePermissions';
import UserListWrapper from '../pages/users/UserListIndex';
import RoleListWrapper from '../pages/roles/RoleListIndex';
import ExercisesWrapper from '../pages/exercises';
import ExerciseCreate from '../pages/exercises/exercise-create/ExerciseCreate';
import ExerciseEdit from '../pages/exercises/exercise-edit/ExerciseEdit';
import ExerciseView from '../pages/exercises/exercise-view/ExerciseView';
import DietListWrapper from '../pages/diets/DietsListWrapper';
import DietWrapper from '../pages/diets';
import CreateDiet from '../pages/diets/diet-create/DietCreate';
import EditDiet from '../pages/diets/diet-edit/DietEdit';
import FoodListWrapper from '../pages/foods/FoodListIndex';
import FoodWrapper from '../pages/foods';
import FoodCreate from '../pages/foods/food-create/FoodCreate';
import FoodEdit from '../pages/foods/food-edit/FoodEdit';
import ExerciseCategoriesWrapper from '../pages/exercise-categories';
import RoutineCategoriesWrapper from '../pages/routine-categories';
import RoutineCategoriesListWrapper from '../pages/routine-categories/RoutineCategoriesListWrapper';
import RoutineCategoryCreate from '../pages/routine-categories/category-create/RoutineCategoryCreate';
import RoutineCategoryEdit from '../pages/routine-categories/category-edit/RoutineCategoryEdit';
import RoutinesWrapper from '../pages/routines';
import RoutinesListWrapper from '../pages/routines/RoutinesListWrapper';
import CreateRoutine from '../pages/routines/routine-create/RoutineCreate';
import EditRoutine from '../pages/routines/routine-edit/RoutineEdit';
import RoutineView from '../pages/routines/routine-view/RoutineView';
import RoutineDaysView from '../pages/routines/routines-list/RoutineDaysView';
import UserCreate from '../pages/users/user-create/UserCreate';
import ExerciseListWrapper from '../pages/exercises/ExerciseListIndex';
import { ExerciseCategoriesListWrapper } from '../pages/exercise-categories/ExerciseCategoriesListWrapper';
import ExerciseCategoryCreate from '../pages/exercise-categories/category-create/ExerciseCategoryCreate';
import ExerciseCategoryEdit from '../pages/exercise-categories/category-edit/ExerciseCategoryEdit';
import EducativeResourcesWrapper from '../pages/educative-resources';
import EducativeResourceCreate from '../pages/educative-resources/educative-create/EducativeResourceCreate';
import EducativeResourceEdit from '../pages/educative-resources/educative-edit/EducativeResourceEdit';
import EducativeResourcesListWrapper from '../pages/educative-resources/EducativeResourceListIndex';
import UserHasRoutinesWrapper from '../pages/user-has-routines';
import UserHasRoutinesListWrapper from '../pages/user-has-routines/UserHasRoutinesListWrapper';
import RoutineRegister from '../pages/user-has-routines/routine-register/RoutineRegister';
import UserHasDietsWrapper from '../pages/user-has-diets';
import UserHasDietsListWrapper from '../pages/user-has-diets/UserHasDietsListWrapper';
import DietView from '../pages/diets/diet-view/DietView';
import DailyIntake from '../pages/user-has-diets/diets-list/DailyIntake';



const protectedRoutes = [
	
	/* ******************** USERS ********************* */
	{
		path: usersMenu.users.path,
		element: <UsersWrapper />,
		sub: [
			{
				element: <UserListWrapper />,
				access: {
					group: 'user',
					action: 'list'
				},
				index: true,
			},
			{
				path: ':id/profile',
				access: {
					group: 'user',
					action: 'get'
				},
				element: <UserProfile />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'user',
					action: 'edit'
				},
				element: <UserEdit />,
			},
			{
				path: 'create',
				access: {
					group: 'user',
					action: 'create'
				},
				element: <UserCreate />,
			}
		]
	},

	/* ******************** EXERCISES ********************* */
	{
		path: adminMenu.exercises.path,
		element: <ExercisesWrapper />,
		sub: [
			{
				element: <ExerciseListWrapper />,
				access: {
					group: 'exercises',
					action: 'list'
				},
				index: true,
			},
			{
				path: 'create',
				access: {
					group: 'exercises',
					action: 'create'
				},
				element: <ExerciseCreate />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'exercises',
					action: 'edit'
				},
				element: <ExerciseEdit />,
			},
			{
				path: ':id/view',
				access: {
					group: 'exercises',
					action: 'get'
				},
				element: <ExerciseView />,
			},
		]
	},

		/* ******************** EXERCISE CATEGORIES ********************* */
		{
			path: superAdminMenu.exerciseCategories.path,
			element: <ExerciseCategoriesWrapper />,
			sub: [
				{
					element: <ExerciseCategoriesListWrapper />,
					access: {
						group: 'exercises',
						action: 'list'
					},
					index: true,
				},
				{
					path: 'create',
					access: {
						group: 'exercises',
						action: 'create'
					},
					element: <ExerciseCategoryCreate />,
				},
				{
					path: ':id/edit',
					access: {
						group: 'exercises',
						action: 'edit'
					},
					element: <ExerciseCategoryEdit />,
				},
			]
		}, 

		/* ******************** ROUTINE CATEGORIES ********************* */
	{
		path: superAdminMenu.routineCategories.path,
		element: <RoutineCategoriesWrapper />,
		sub: [
			{
				element: <RoutineCategoriesListWrapper />,
				access: {
					group: 'routines',
					action: 'list'
				},
				index: true,
			},
			{
				path: 'create',
				access: {
					group: 'routines',
					action: 'create'
				},
				element: <RoutineCategoryCreate />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'routines',
					action: 'edit'
				},
				element: <RoutineCategoryEdit />,
			},
		]
	},


	/* ******************** ROUTINES ********************* */
	{
		path: adminMenu.routines.path,
		element: <RoutinesWrapper />,
		sub: [
			{
				element: <RoutinesListWrapper />,
				access: {
					group: 'routines',
					action: 'list'
				},
				index: true,
			},
			{
				path: 'create',
				access: {
					group: 'routines',
					action: 'create'
				},
				element: <CreateRoutine />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'routines',
					action: 'edit'
				},
				element: <EditRoutine />,
			},
			{
				path: ':id/',
				access: {
					group: 'routines',
					action: 'get'
				},
				element: <RoutineDaysView />,
			},
			{
				path: ':id/:dayNumber/view',
				access: {
					group: 'routines',
					action: 'get'
				},
				element: <RoutineView />,
			},
			{
				path: ':id/:dayNumber/routine-register',
				access: {
					group: 'routines',
					action: 'get'
				},
				element: <RoutineRegister/>,
			}
			
		]
	},

	{
		path: adminMenu.userHasRoutines.path,
		element: <UserHasRoutinesWrapper />,
		sub: [
			{
				element: <UserHasRoutinesListWrapper />,
				access: {
					group: 'routines',
					action: 'list'
				},
				index: true,
			},
			{
				path: 'create',
				access: {
					group: 'routines',
					action: 'create'
				},
				element: <CreateRoutine />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'routines',
					action: 'edit'
				},
				element: <EditRoutine />,
			},
			{
				path: ':id/',
				access: {
					group: 'routines',
					action: 'get'
				},
				element: <RoutineDaysView />,
			},
			{
				path: ':id/:dayNumber/view',
				access: {
					group: 'routines',
					action: 'get'
				},
				element: <RoutineView />,
			},
			
		]
	},

	/* ******************** DIETS ********************* */
	{
		path: adminMenu.diets.path,
		element: <DietWrapper />,
		sub: [
			{
				element: <DietListWrapper />,
				access: {
					group: 'diets',
					action: 'list'
				},
				index: true,
			},
			{
				path: 'create',
				access: {
					group: 'diets',
					action: 'create'
				},
				element: <CreateDiet />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'diets',
					action: 'edit'
				},
				element: <EditDiet />,
			},
			{
				path: ':id/view',
				access: {
					group: 'diets',
					action: 'get'
				},
				element: <DietView />,
			},			
		]
	},

		{
		path: adminMenu.userHasDiets.path,
		element: <UserHasDietsWrapper />,
		sub: [
			{
				element: <UserHasDietsListWrapper />,
				access: {
					group: 'diets',
					action: 'list'
				},
				index: true,
			},
			{
				path: 'create',
				access: {
					group: 'diets',
					action: 'create'
				},
				element: <CreateDiet />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'diets',
					action: 'edit'
				},
				element: <EditDiet />,
			},
			{
				path: ':id/view',
				access: {
					group: 'diets',
					action: 'get'
				},
				element: <DietView />,
			},
			{
				path: 'daily-intake',
				access: {
					group: 'diets',
					action: 'get'
				},
				element: <DailyIntake />,
			}
		]
	},

	/* ******************** FOOD ********************* */
	{
		path: superAdminMenu.food.path,
		element: <FoodWrapper />,
		sub: [
			{
				element: <FoodListWrapper />,
				access: {
					group: 'food',
					action: 'list'
				},
				index: true
			},
			{
				path: 'create',
				access: {
					group: 'food',
					action: 'create'
				},
				element: <FoodCreate />,
			},
			{
				path: ':id/edit',
				access: {
					group: 'food',
					action: 'edit'
				},
				element: <FoodEdit />,
			},
		]
	},

	/* ******************** ROLES ********************* */
	{
		path: superAdminMenu.roles.path,
		element: <RoleWrapper />,
		isProtected: true,
		sub: [
			{
				element: <RoleListWrapper />,
				access: {
					group: 'roles',
					action: 'list'
				},
				index: true
			},
			{
				path: ':id/edit',
				access: {
					group: 'roles',
					action: 'edit'
				},
				element: <RoleEditPermissions />,
			}
		]
	},

	    /* ******************** EDUCATIVE RESOURCES ********************* */
    {
        path: adminMenu.educativeResources.path, 
        element: <EducativeResourcesWrapper />,
        sub: [
            {
                element: <EducativeResourcesListWrapper />,
                access: {
                    group: 'educative_resources',
                    action: 'list'
                },
                index: true,
            },
            {
                path: 'create',
                access: {
                    group: 'educative_resources',
                    action: 'create'
                },
                element: <EducativeResourceCreate />,
            },
            {
                path: ':id/edit',
                access: {
                    group: 'educative_resources',
                    action: 'edit'
                },
                element: <EducativeResourceEdit />,
            },
        ]
    },

	/** ************************************************** */
	{
		path: permissionsPage.permissions.path,
		element: <PagePermissions />,
	},

];
const contents = [...protectedRoutes];

export default contents;