export const routinesMenu = {
	routines: {
		id: 'routines',
		text: 'Rutinas',
		path: '/routines',
		icon: 'FitnessCenter',
		permissions_required: {
			entity: 'routines',
			action: 'admin_routines'
		},
	},
};

export const userHasRoutineMenu = {
	userHasRoutines: {
		id: 'userRoutines',
		text: 'Mis Rutinas',
		path: '/user-routines',
		icon: 'FitnessCenter', 
		permissions_required: {
			entity: 'routines',
			action: 'list'
		},
	},
};


export const userHasDietMenu = {
	userHasDiets: {
		id: 'userDiets',
		text: 'Mis Dietas',
		path: '/user-diets',
		icon: 'Restaurant',
		permissions_required: {
			entity: 'diets',
			action: 'list'
		},
	},
};

export const dietMenu = {
	diets: {
		id: 'diets',
		text: 'Dietas',
		path: '/diets',
		icon: 'Restaurant',
		permissions_required: {
			entity: 'diets',
			action: 'list'
		},
	},
};

export const profileMenu = {
	profile: {
		id: 'profile',
		text: 'Perfil',
		path: '/users',
		icon: 'Person',
		permissions_required: {
			entity: 'users',
			action: 'get'
		},
	},
};

export const exerciseCategoryMenu = {
	exerciseCategories: {
		id: 'exerciseCategories',
		text: 'Categorías de ejercicios',
		path: '/exercise-categories',
		icon: 'Category',
		permissions_required: {
			entity: 'exercises',
			action: 'admin_exercises'
		},
	},
}

export const exerciseMenu = {
	exercises: {
		id: 'exercises',
		text: 'Ejercicios',
		path: '/exercises',
		icon: 'Inventory2',
		permissions_required: {
			entity: 'exercises',
			action: 'admin_exercises'
		},
	},
};



export const educativeResourcesMenu = {
	educativeResources: {
		id: 'educativeResources',
		text: 'Recursos educativos',
		path: '/educative-resources',
		icon: 'LibraryBooks',
		permissions_required: {
			entity: 'educative_resources',
			action: 'list'
		},
	},
};


export const usersMenu = {
	users: {
		id: 'users',
		text: 'Usuarios',
		path: '/users',
		icon: 'Person',
		permissions_required: {
			entity: 'user',
			action: 'admin_user'
		},
	},
};

export const adminMenu = {
	title: {
		id: 'title-admin',
		text: 'Administración',
		icon: 'Extension',
	},
	...routinesMenu,
	...dietMenu,
	...exerciseMenu,
	...educativeResourcesMenu,
	...usersMenu,
	...userHasRoutineMenu,
	...userHasDietMenu
};

export const superAdminMenu = {
	title: {
		id: 'title-super-admin',
		text: 'SuperAdmin',
		icon: 'Extension',
	},
	roles: {
		id: 'roles',
		text: 'Roles',
		path: '/roles',
		icon: 'AdminPanelSettings',
		subMenu: null,
	},
	exerciseCategories: {
		id: 'exerciseCategories',
		text: 'Categorías de ejercicios',
		path: '/exercise-categories',
		icon: 'Category',
		permissions_required: {
				entity: 'exercises',
				action: 'admin_exercises'
		},
	},
	routineCategories: {
		id: 'routineCategories',
		text: 'Categorías de rutinas',
		path: '/routine-categories',
		icon: 'Category',
		permissions_required: {
			entity: 'routines',
			action: 'admin_routines'
		},
	},
	food: {
		id: 'food',
		text: 'Alimentos',
		path: '/food',
		icon: 'Fastfood',
		permissions_required: {
			entity: 'food',
			action: 'admin_food'
		},
	},
};

export const permissionsPage = {
	permissions: {
		id: 'permissions',
		text: 'Permisos',
		path: '/permissions',
		subMenu: null,
	},	
};

export const routineCategoryMenu = {
	routineCategories: {
		id: 'routineCategories',
		text: 'Categorías de rutinas',
		path: '/routine-categories',
		icon: 'Category',
		permissions_required: {
			entity: 'routines',
			action: 'admin_routines'
		},
	},
};