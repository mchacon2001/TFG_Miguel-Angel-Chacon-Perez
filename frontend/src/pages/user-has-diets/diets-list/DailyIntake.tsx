import React, { Fragment, useState, useCallback, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import moment from "moment";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";

import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle, CardBody } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";
import FormGroup from "../../../components/bootstrap/forms/FormGroup";
import Label from "../../../components/bootstrap/forms/Label";
import Input from "../../../components/bootstrap/forms/Input";
import SearchableSelect from "../../../components/SearchableSelect";
import Spinner from "../../../components/bootstrap/Spinner";
import useFetch from "../../../hooks/useFetch";
import useFilters from "../../../hooks/useFilters";

import { UserHasDietService } from "../../../services/user-has-diet/userHasDietService";
import { FoodService } from "../../../services/foods/foodService";

interface FoodIntake {
    foodId: string;
    quantity: number;
}

interface MealIntake {
    breakfast: FoodIntake[];
    midMorningSnack: FoodIntake[];
    lunch: FoodIntake[];
    afternoonSnack: FoodIntake[];
    dinner: FoodIntake[];
}

interface DietFood {
    id: string;
    food: {
        name: string;
        calories: number;
        proteins?: number;
        carbs?: number;
        fats?: number;
    };
    quantity: number;
    unit: string;
    mealType: string;
}

interface DietDay {
    dayOfWeek: string;
    foods: DietFood[];
}

const DailyIntakePage = () => {
    const navigate = useNavigate();
    const userHasDietService = new UserHasDietService();
    const user = useSelector((state: RootState) => state.auth.user);
    
    const [mealIntake, setMealIntake] = useState<MealIntake>({
        breakfast: [],
        midMorningSnack: [],
        lunch: [],
        afternoonSnack: [],
        dinner: []
    });

    const [openAccordions, setOpenAccordions] = useState<string[]>([]);
    const [openDietAccordions, setOpenDietAccordions] = useState<string[]>([]);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isLoadingExistingData, setIsLoadingExistingData] = useState(false);
    const [hasExistingData, setHasExistingData] = useState(false);

    const [dietData, setDietData] = useState<any>(null);
    const [dietLoading, setDietLoading] = useState(false);
    const [dietError, setDietError] = useState<any>(null);

    const { filters: foodFilters } = useFilters({}, [], 1, 1000);

    const [foodsApiData, foodsLoading, foodsError] = useFetch(
        useCallback(async () => {
            const response = await new FoodService().getFood(foodFilters);
            return response.getResponseData();
        }, [foodFilters])
    );

    const foodsData = Array.isArray(foodsApiData)
        ? foodsApiData
        : (foodsApiData && Array.isArray(foodsApiData.food))
        ? foodsApiData.food
        : [];

    const foodOptions = foodsData.map((food: any) => ({
        value: food.id,
        label: food.name
    }));

    const toggleAccordion = (mealKey: string) => {
        setOpenAccordions(prev => {
            if (prev.includes(mealKey)) {
                return prev.filter(key => key !== mealKey);
            } else {
                return [...prev, mealKey];
            }
        });
    };

    const toggleDietAccordion = (mealType: string) => {
        setOpenDietAccordions(prev => {
            if (prev.includes(mealType)) {
                return prev.filter(type => type !== mealType);
            } else {
                return [...prev, mealType];
            }
        });
    };

    const getCurrentDayOfWeek = () => {
        const daysMap: { [key: string]: string } = {
            'Monday': 'Lunes',
            'Tuesday': 'Martes',
            'Wednesday': 'Miércoles',
            'Thursday': 'Jueves',
            'Friday': 'Viernes',
            'Saturday': 'Sábado',
            'Sunday': 'Domingo'
        };
        const today = moment().format('dddd');
        return daysMap[today] || 'Lunes';
    };

    const currentDay = getCurrentDayOfWeek();

    // Función para obtener datos de ingesta diaria existentes
    const getDailyIntake = async (userId: string, date: string) => {
        try {
            const response = await userHasDietService.getDailyIntake({
                userId,
                date
            });
            return response.getResponseData();
        } catch (error) {
            console.error('Error fetching daily intake:', error);
            return { success: false, data: null };
        }
    };

    // Función para cargar datos existentes
const fetchExistingIntake = async () => {
    if (!user?.id) return;
    
    setIsLoadingExistingData(true);
    try {
        const today = moment().format('YYYY-MM-DD');
        const response = await getDailyIntake(user.id, today);
        
        if (response.success && response.data?.meals) {
            // Mapear tipos de comida del backend al frontend
            const mealTypeMapping: { [key: string]: keyof MealIntake } = {
                'breakfast': 'breakfast',
                'midMorningSnack': 'midMorningSnack',
                'lunch': 'lunch',
                'afternoonSnack': 'afternoonSnack',
                'dinner': 'dinner'
            };

            // Convertir los datos del backend al formato del frontend
            const existingMeals: MealIntake = {
                breakfast: [],
                midMorningSnack: [],
                lunch: [],
                afternoonSnack: [],
                dinner: []
            };

            // Procesar cada tipo de comida - Corrección aquí
            Object.entries(response.data.meals).forEach(([mealType, foods]) => {
                const frontendMealType = mealTypeMapping[mealType];
                if (frontendMealType && Array.isArray(foods) && foods.length > 0) {
                    existingMeals[frontendMealType] = foods.map((food: any) => ({
                        foodId: food.food.id,
                        quantity: food.quantity
                    }));
                }
            });

            setMealIntake(existingMeals);
            setHasExistingData(true);
            
            // Abrir los acordeones que tienen datos
            const mealsWithData = Object.entries(existingMeals)
                .filter(([_, foods]) => foods.length > 0)
                .map(([mealType, _]) => mealType);
            
            setOpenAccordions(mealsWithData);
            
            if (mealsWithData.length > 0) {
                setHasExistingData(true);
            }
        } else {
            setHasExistingData(false);
        }
    } catch (error) {
        console.error('Error al cargar ingesta existente:', error);
        setHasExistingData(false);
    } finally {
        setIsLoadingExistingData(false);
    }
};

// Función para refrescar los datos - Corrección aquí también
const refreshIntakeData = async () => {
    if (!user?.id) return;
    
    try {
        const today = moment().format('YYYY-MM-DD');
        const response = await getDailyIntake(user.id, today);
        
        if (response.success && response.data?.meals) {
            const mealTypeMapping: { [key: string]: keyof MealIntake } = {
                'breakfast': 'breakfast',
                'midMorningSnack': 'midMorningSnack',
                'lunch': 'lunch',
                'afternoonSnack': 'afternoonSnack',
                'dinner': 'dinner'
            };

            const existingMeals: MealIntake = {
                breakfast: [],
                midMorningSnack: [],
                lunch: [],
                afternoonSnack: [],
                dinner: []
            };

            // Corrección aquí
            Object.entries(response.data.meals).forEach(([mealType, foods]) => {
                const frontendMealType = mealTypeMapping[mealType];
                if (frontendMealType && Array.isArray(foods) && foods.length > 0) {
                    existingMeals[frontendMealType] = foods.map((food: any) => ({
                        foodId: food.food.id,
                        quantity: food.quantity
                    }));
                }
            });

            setMealIntake(existingMeals);
            setHasExistingData(true);
            toast.success("Datos actualizados desde el servidor");
        } else {
            setMealIntake({
                breakfast: [],
                midMorningSnack: [],
                lunch: [],
                afternoonSnack: [],
                dinner: []
            });
            setHasExistingData(false);
        }
    } catch (error) {
        console.error('Error al actualizar datos:', error);
        toast.error("Error al actualizar los datos");
    }
};

    // Cargar dieta del usuario
    useEffect(() => {
        const fetchDiet = async () => {
            if (!user?.id) return;
            
            setDietLoading(true);
            setDietError(null);
            
            try {
                const filtersWithUser = {
                    filter_filters: {
                        user: user.id,
                        selectedDiet: true,
                    },
                    filter_order: [],
                    limit: 1,
                    page: 1,
                };

                const response = await userHasDietService.listUserHasDiets(filtersWithUser);
                const responseData = response.getResponseData();

                if (responseData?.data?.diets && responseData.data.diets.length > 0) {
                    setDietData(responseData.data.diets[0]);
                } else {
                    setDietData(null);
                }
            } catch (error) {
                setDietError(error);
            } finally {
                setDietLoading(false);
            }
        };

        fetchDiet();
    }, [user?.id]);

    // Cargar datos existentes al montar el componente
    useEffect(() => {
        fetchExistingIntake();
    }, [user?.id]);

    const addFood = (mealType: keyof MealIntake) => {
        setMealIntake(prev => ({
            ...prev,
            [mealType]: [...prev[mealType], { foodId: "", quantity: 0 }]
        }));
    };

    const updateFood = (mealType: keyof MealIntake, foodIndex: number, field: string, value: any) => {
        if (field === "quantity") {
            if (value === "" || value === null) {
                value = "";
            } else {
                const parsed = parseFloat(value);
                value = isNaN(parsed) ? value : parsed;
            }
        }
        
        setMealIntake(prev => {
            const updatedMeal = [...prev[mealType]];
            updatedMeal[foodIndex] = { ...updatedMeal[foodIndex], [field]: value };
            return { ...prev, [mealType]: updatedMeal };
        });
    };

    const removeFood = (mealType: keyof MealIntake, foodIndex: number) => {
        setMealIntake(prev => ({
            ...prev,
            [mealType]: prev[mealType].filter((_, index) => index !== foodIndex)
        }));
    };

    const getMealCalories = (mealType: keyof MealIntake) => {
        return mealIntake[mealType].reduce((total, food) => {
            const foodData = foodsData.find((f: any) => f.id === food.foodId);
            if (!foodData || !food.quantity) return total;
            return total + (foodData.calories / 100) * food.quantity;
        }, 0);
    };

    const getTodayFoods = (): DietFood[] => {
        if (!dietData?.diet?.dietHasFood) {
            return [];
        }

        const todayFoods = dietData.diet.dietHasFood.filter((dietFood: any) => {
            return dietFood.dayOfWeek === currentDay;
        });

        return todayFoods.map((dietFood: any) => ({
            id: dietFood.id,
            food: {
                name: dietFood.food.name,
                calories: dietFood.food.calories,
                proteins: dietFood.food.proteins,
                carbs: dietFood.food.carbs,
                fats: dietFood.food.fats,
            },
            quantity: dietFood.amount,
            unit: 'g',
            mealType: dietFood.mealType,
        }));
    };

    const getTodayFoodsByMeal = () => {
        const foods = getTodayFoods();
        const mealTypes = ['Desayuno', 'Media Mañana', 'Almuerzo', 'Merienda', 'Cena'];

        return mealTypes.map((mealType) => ({
            mealType,
            foods: foods.filter((food: any) => food.mealType === mealType),
        })).filter((meal) => meal.foods.length > 0);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);

        try {
            const mealsToSubmit = Object.entries(mealIntake)
                .map(([mealType, foods]) => ({
                    mealType,
                    foods: foods.filter((food: FoodIntake) => food.foodId && food.quantity > 0)
                }))
                .filter(meal => meal.foods.length > 0);

            if (mealsToSubmit.length === 0) {
                toast.error("Debes añadir al menos un alimento");
                setIsSubmitting(false);
                return;
            }

            const dailyIntakeData = {
                userId: user?.id,
                meals: mealsToSubmit
            };


            const response = await userHasDietService.createDailyIntake(dailyIntakeData);
            const responseData = response.getResponseData();
            
            if (responseData.success) {
                toast.success(hasExistingData ? "Ingesta diaria actualizada correctamente" : "Ingesta diaria guardada correctamente");
                setHasExistingData(true);
                // No limpiar el formulario después de guardar para permitir ediciones
            } else {
                toast.error("Error al guardar la ingesta diaria");
            }
        } catch (error) {
            console.error('Error:', error);
            toast.error("Error al comunicarse con el servidor");
        } finally {
            setIsSubmitting(false);
        }
    };

    const clearIntake = () => {
        setMealIntake({
            breakfast: [],
            midMorningSnack: [],
            lunch: [],
            afternoonSnack: [],
            dinner: []
        });
        setOpenAccordions([]);
        setHasExistingData(false);
    };

    const mealLabels = {
        breakfast: 'Desayuno',
        midMorningSnack: 'Media Mañana',
        lunch: 'Almuerzo',
        afternoonSnack: 'Merienda',
        dinner: 'Cena'
    };

    return (
        <Fragment>
            <style>{`
            .scrollable-column {
                max-height: 80vh;
                overflow-y: auto;
                overflow-x: hidden;
            }
            
            .scrollable-column::-webkit-scrollbar {
                width: 6px;
            }
            
            .scrollable-column::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }
            
            .scrollable-column::-webkit-scrollbar-thumb {
                background: #ccc;
                border-radius: 3px;
            }
            
            .scrollable-column::-webkit-scrollbar-thumb:hover {
                background: #999;
            }
        `}</style>
            <SubHeader>
                <SubHeaderLeft>
                    <Fragment>
                        <Button
                            icon="ArrowBack"
                            color="light"
                            isLight
                            onClick={() => navigate("/user-diets")}
                        >
                            Volver
                        </Button>
                        <SubheaderSeparator />
                        <CardTitle>Ingesta Diaria - {moment().format('DD/MM/YYYY')}</CardTitle>
                    </Fragment>
                </SubHeaderLeft>
            </SubHeader>

            <Page container="fluid">
                <div className="row">
                    <div className="col-lg-6">
                        <div className="scrollable-column">
                            <Card>
                                <CardBody>
                                    <div className="d-flex align-items-center mb-4">
                                        <div className="flex-grow-1">
                                            <h3 className="mb-1">Registro de Comidas</h3>
                                            <p className="text-muted mb-0">
                                                Registra lo que has consumido hoy ({currentDay})
                                                {isLoadingExistingData && (
                                                    <span className="badge bg-info ms-2">
                                                        <Spinner isSmall className="me-1" />
                                                        Cargando...
                                                    </span>
                                                )}
                                                {!isLoadingExistingData && hasExistingData && (
                                                    <span className="badge bg-success ms-2">
                                                        Datos cargados
                                                    </span>
                                                )}
                                            </p>
                                        </div>
                                        <Button
                                            type="button"
                                            color="primary"
                                            isLight
                                            icon="Refresh"
                                            size="sm"
                                            onClick={refreshIntakeData}
                                            title="Actualizar datos desde el servidor"
                                        >
                                        </Button>
                                    </div>

                                    <form onSubmit={handleSubmit}>
                                        <div className="accordion" id="intakeAccordion">
                                            {Object.entries(mealLabels).map(([mealKey, mealLabel], mealIndex) => (
                                                <div className="accordion-item mb-3" key={mealKey}>
                                                    <h2 className="accordion-header">
                                                        <button
                                                            className={`accordion-button bg-warning ${openAccordions.includes(mealKey) ? '' : 'collapsed'}`}
                                                            type="button"
                                                            onClick={() => toggleAccordion(mealKey)}
                                                            aria-expanded={openAccordions.includes(mealKey)}
                                                            style={{
                                                                fontWeight: 600,
                                                                color: "#000",
                                                                border: "1px solid #dee2e6",
                                                                borderRadius: "1.5rem",
                                                                transition: "background 0.2s, border-radius 0.2s, color 0.2s"
                                                            }}
                                                        >
                                                            <div className="d-flex justify-content-between align-items-center w-100 me-3">
                                                                <span>{mealLabel}</span>
                                                                <span className="badge bg-light text-dark">
                                                                    {Math.round(getMealCalories(mealKey as keyof MealIntake))} kcal
                                                                </span>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div
                                                        id={`collapse-${mealKey}`}
                                                        className={`accordion-collapse collapse ${openAccordions.includes(mealKey) ? 'show' : ''}`}
                                                    >
                                                        <div className="accordion-body">
                                                            <div className="d-flex justify-content-between align-items-center mb-3">
                                                                <h6 className="mb-0">Alimentos consumidos</h6>
                                                                <Button 
                                                                    type="button" 
                                                                    size="sm" 
                                                                    color="primary"
                                                                    onClick={() => addFood(mealKey as keyof MealIntake)}
                                                                >
                                                                    <i className="bi bi-plus-circle me-1"></i>
                                                                    Añadir alimento
                                                                </Button>
                                                            </div>

                                                            {mealIntake[mealKey as keyof MealIntake].map((food, foodIndex) => (
                                                                <div key={foodIndex} className="border rounded p-3 mb-3 bg-light">
                                                                    <div className="row g-3 align-items-end">
                                                                        <div className="col-md-7">
                                                                            <FormGroup label="Alimento" className="mb-0">
                                                                                <SearchableSelect
                                                                                    name={`food-${mealKey}-${foodIndex}`}
                                                                                    placeholder="Selecciona un alimento"
                                                                                    value={foodOptions.find((option: any) => option.value === food.foodId) || null}
                                                                                    onChange={(option: any) =>
                                                                                        updateFood(mealKey as keyof MealIntake, foodIndex, 'foodId', option?.value || '')
                                                                                    }
                                                                                    options={foodOptions}
                                                                                    isSearchable
                                                                                />
                                                                            </FormGroup>
                                                                        </div>
                                                                        <div className="col-md-3">
                                                                            <FormGroup label="Cantidad (g)" className="mb-0">
                                                                                <Input
                                                                                    type="text"
                                                                                    placeholder="0"
                                                                                    value={food.quantity === 0 ? "" : food.quantity.toString()}
                                                                                    onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                                                                        const val = e.target.value;
                                                                                        if (/^\d*(\.?\d*)?$/.test(val)) {
                                                                                            updateFood(mealKey as keyof MealIntake, foodIndex, "quantity", val);
                                                                                        }
                                                                                    }}
                                                                                />
                                                                            </FormGroup>
                                                                        </div>
                                                                        <div className="col-md-2 d-flex justify-content-end align-items-end">
                                                                            <Button
                                                                                type="button"
                                                                                size="lg"
                                                                                className="mb-2"
                                                                                icon="Delete"
                                                                                onClick={() => removeFood(mealKey as keyof MealIntake, foodIndex)}
                                                                                style={{ 
                                                                                    width: '36px',
                                                                                    height: '36px',
                                                                                    padding: '0',
                                                                                    border: 'none'
                                                                                }}
                                                                                title="Eliminar alimento"
                                                                            >
                                                                            </Button>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    {food.foodId && food.quantity > 0 && (() => {
                                                                        const foodData = foodsData.find((f: any) => f.id === food.foodId);
                                                                        if (!foodData) return null;
                                                                        
                                                                        const calories = Math.round((foodData.calories / 100) * food.quantity);
                                                                        const proteins = Math.round(((foodData.proteins || 0) / 100) * food.quantity);
                                                                        const carbs = Math.round(((foodData.carbs || 0) / 100) * food.quantity);
                                                                        const fats = Math.round(((foodData.fats || 0) / 100) * food.quantity);
                                                                        
                                                                        return (
                                                                            <div className="mt-3 pt-3 border-top">
                                                                                <div className="row g-2">
                                                                                    <div className="col-3">
                                                                                        <div className="text-center p-2 bg-light rounded">
                                                                                            <div className="fw-bold text-success fs-6">{calories}</div>
                                                                                            <small className="text-muted">kcal</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div className="col-3">
                                                                                        <div className="text-center p-2 bg-light rounded">
                                                                                            <div className="fw-bold text-primary fs-6">{proteins}g</div>
                                                                                            <small className="text-muted">Proteínas</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div className="col-3">
                                                                                        <div className="text-center p-2 bg-light rounded">
                                                                                            <div className="fw-bold text-dark fs-6">{carbs}g</div>
                                                                                            <small className="text-muted">Carbohidratos</small>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div className="col-3">
                                                                                        <div className="text-center p-2 bg-light rounded">
                                                                                            <div className="fw-bold text-dark fs-6">{fats}g</div>
                                                                                            <small className="text-muted">Grasas</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        );
                                                                    })()}
                                                                </div>
                                                            ))}

                                                            {mealIntake[mealKey as keyof MealIntake].length === 0 && (
                                                                <div className="text-center py-4 text-muted">
                                                                    <i className="bi bi-plus-circle fs-1 mb-2"></i>
                                                                    <p className="mb-0">No has añadido alimentos para {mealLabel.toLowerCase()}</p>
                                                                </div>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>

                                        <div className="mt-4">
                                            <Card className="border-0 shadow-sm">
                                                <CardBody className="p-4">
                                                    <h6 className="mb-4 text-center fw-bold">
                                                        📊 Resumen total del día
                                                    </h6>
                                                    <div className="row g-3 text-center">
                                                        <div className="col-6 col-md-3">
                                                            <div className="p-3 bg-dark text-white rounded-3 shadow">
                                                                <div className="fw-bold fs-3 mb-1">
                                                                    {Math.round(Object.keys(mealIntake).reduce((total, mealKey) => 
                                                                        total + getMealCalories(mealKey as keyof MealIntake), 0
                                                                    ))}
                                                                </div>
                                                                <small className="text-light">Calorías</small>
                                                            </div>
                                                        </div>
                                                        <div className="col-6 col-md-3">
                                                            <div className="p-3 text-white rounded-3 shadow" style={{backgroundColor: '#e74c3c'}}>
                                                                <div className="fw-bold fs-3 mb-1">
                                                                    {Object.keys(mealIntake).reduce((total, mealKey) => {
                                                                        return total + mealIntake[mealKey as keyof MealIntake].reduce((mealTotal, food) => {
                                                                            const foodData = foodsData.find((f: any) => f.id === food.foodId);
                                                                            if (!foodData || !food.quantity) return mealTotal;
                                                                            return mealTotal + Math.round(((foodData.proteins || 0) / 100) * food.quantity);
                                                                        }, 0);
                                                                    }, 0)}g
                                                                </div>
                                                                <small className="text-light">Proteínas</small>
                                                            </div>
                                                        </div>
                                                        <div className="col-6 col-md-3">
                                                            <div className="p-3 text-dark rounded-3 shadow" style={{backgroundColor: '#f39c12'}}>
                                                                <div className="fw-bold fs-3 mb-1">
                                                                    {Object.keys(mealIntake).reduce((total, mealKey) => {
                                                                        return total + mealIntake[mealKey as keyof MealIntake].reduce((mealTotal, food) => {
                                                                            const foodData = foodsData.find((f: any) => f.id === food.foodId);
                                                                            if (!foodData || !food.quantity) return mealTotal;
                                                                            return mealTotal + Math.round(((foodData.carbs || 0) / 100) * food.quantity);
                                                                        }, 0);
                                                                    }, 0)}g
                                                                </div>
                                                                <small className="text-dark">Carbohidratos</small>
                                                            </div>
                                                        </div>
                                                        <div className="col-6 col-md-3">
                                                            <div className="p-3 text-white rounded-3 shadow" style={{backgroundColor: '#9b59b6'}}>
                                                                <div className="fw-bold fs-3 mb-1">
                                                                    {Object.keys(mealIntake).reduce((total, mealKey) => {
                                                                        return total + mealIntake[mealKey as keyof MealIntake].reduce((mealTotal, food) => {
                                                                            const foodData = foodsData.find((f: any) => f.id === food.foodId);
                                                                            if (!foodData || !food.quantity) return mealTotal;
                                                                            return mealTotal + Math.round(((foodData.fats || 0) / 100) * food.quantity);
                                                                        }, 0);
                                                                    }, 0)}g
                                                                </div>
                                                                <small className="text-light">Grasas</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="mt-4 text-center">
                                                        <div className="badge bg-secondary fs-6 px-3 py-2">
                                                            Total de alimentos: {Object.keys(mealIntake).reduce((total, mealKey) => 
                                                                total + mealIntake[mealKey as keyof MealIntake].length, 0
                                                            )}
                                                        </div>
                                                    </div>
                                                </CardBody>
                                            </Card>
                                        </div>

                                        <div className="d-flex gap-3 mt-4">
                                            <Button
                                                type="submit"
                                                color="success"
                                                size="lg"
                                                isDisable={isSubmitting}
                                                className="flex-fill"
                                            >
                                                {isSubmitting ? (
                                                    <>
                                                        <Spinner isSmall className="me-2" />
                                                        Guardando...
                                                    </>
                                                ) : (
                                                    <>
                                                        <i className="bi bi-check-circle me-2"></i>
                                                        {hasExistingData ? 'Actualizar Ingesta' : 'Guardar Ingesta'}
                                                    </>
                                                )}
                                            </Button>
                                            <Button
                                                type="button"
                                                color="info"
                                                isLight
                                                size="lg"
                                                onClick={refreshIntakeData}
                                                title="Sincronizar con el servidor"
                                            >
                                                <i className="bi bi-arrow-clockwise me-2"></i>
                                                Sincronizar
                                            </Button>
                                            <Button
                                                type="button"
                                                color="light"
                                                isLight
                                                size="lg"
                                                onClick={clearIntake}
                                            >
                                                <i className="bi bi-trash me-2"></i>
                                                Limpiar
                                            </Button>
                                        </div>
                                    </form>
                                </CardBody>
                            </Card>
                        </div>
                    </div>

                    <div className="col-lg-6">
                        <div className="scrollable-column">
                            <Card>
                                <CardBody>
                                    <div className="d-flex align-items-center mb-4">
                                        <i className="bi bi-calendar-day fs-2 text-warning me-3"></i>
                                        <div>
                                            <h3 className="mb-1">Dieta de Hoy</h3>
                                            <p className="text-muted mb-0">
                                                Alimentos recomendados para {currentDay}
                                            </p>
                                        </div>
                                    </div>

                                    {dietLoading && <Loader />}
                                    {dietError && <ErrorMessage />}

                                    {!dietLoading && !dietError && (
                                        <div>
                                            {dietData && dietData.diet ? (
                                                <div>
                                                    <div className="mb-4 p-4 bg-gradient rounded-3 shadow-sm">
                                                        <div className="d-flex align-items-center justify-content-between mb-3">
                                                            <h5 className="text-primary mb-0 fw-bold">📋 {dietData.diet?.name || "Sin nombre"}</h5>
                                                            {dietData.diet?.goal && (
                                                                <span className="badge bg-warning text-dark fs-6 px-3 py-2">
                                                                    🎯 {dietData.diet.goal} kcal
                                                                </span>
                                                            )}
                                                        </div>
                                                        <p className="text-muted mb-0">{dietData.diet?.description || "Sin descripción"}</p>
                                                    </div>

                                                    <div className="diet-content">
                                                        {getTodayFoodsByMeal().length > 0 ? (
                                                            <>
                                                                {getTodayFoodsByMeal().map((meal, mealIndex) => (
                                                                    <Card key={mealIndex} className="mb-3 border-0 shadow-sm">
                                                                        <CardBody className="p-0">
                                                                            <div 
                                                                                className="bg-warning text-dark p-3 rounded-top"
                                                                                style={{ cursor: 'pointer' }}
                                                                                onClick={() => toggleDietAccordion(meal.mealType)}
                                                                            >
                                                                                <h6 className="mb-0 fw-bold d-flex align-items-center justify-content-between">
                                                                                    <span>
                                                                                        <i className="bi bi-clock me-2"></i>
                                                                                        {meal.mealType}
                                                                                    </span>
                                                                                    <div className="d-flex align-items-center">
                                                                                        <span className="badge bg-light text-dark me-2">
                                                                                            {meal.foods.reduce((total, food) => 
                                                                                                total + Math.round((food.food.calories * food.quantity) / 100), 0
                                                                                            )} kcal
                                                                                        </span>
                                                                                        <i className={`bi bi-chevron-${openDietAccordions.includes(meal.mealType) ? 'up' : 'down'}`}></i>
                                                                                    </div>
                                                                                </h6>
                                                                            </div>
                                                                            
                                                                            {openDietAccordions.includes(meal.mealType) && (
                                                                                <div className="p-3">
                                                                                    {meal.foods.map((dietFood: any, foodIndex: number) => (
                                                                                        <div key={foodIndex} className={`border-bottom py-3 ${foodIndex === meal.foods.length - 1 ? 'border-0' : ''}`}>
                                                                                            <div className="d-flex justify-content-between align-items-start mb-2">
                                                                                                <div className="flex-grow-1">
                                                                                                    <h6 className="mb-1 text-dark fw-bold">
                                                                                                        {dietFood.food.name}
                                                                                                    </h6>
                                                                                                    <div className="d-flex align-items-center mb-2">
                                                                                                        <span className="badge bg-light text-dark me-2">
                                                                                                            {dietFood.quantity} {dietFood.unit}
                                                                                                        </span>
                                                                                                        <small className="text-muted">
                                                                                                            {dietFood.food.calories} kcal/100g
                                                                                                        </small>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div className="text-end">
                                                                                                    <div className="fw-bold text-success fs-5">
                                                                                                        {Math.round((dietFood.food.calories * dietFood.quantity) / 100)} kcal
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div className="row g-2 mt-2">
                                                                                                <div className="col-4">
                                                                                                    <div className="text-center p-2 bg-light rounded">
                                                                                                        <div className="fw-bold text-primary">
                                                                                                            {dietFood.food.proteins ? Math.round((dietFood.food.proteins * dietFood.quantity) / 100) : 0}g
                                                                                                        </div>
                                                                                                        <small className="text-muted">Proteínas</small>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div className="col-4">
                                                                                                    <div className="text-center p-2 bg-light rounded">
                                                                                                        <div className="fw-bold text-dark">
                                                                                                            {dietFood.food.carbs ? Math.round((dietFood.food.carbs * dietFood.quantity) / 100) : 0}g
                                                                                                        </div>
                                                                                                        <small className="text-muted">Carbohidratos</small>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div className="col-4">
                                                                                                    <div className="text-center p-2 bg-light rounded">
                                                                                                        <div className="fw-bold text-dark">
                                                                                                            {dietFood.food.fats ? Math.round((dietFood.food.fats * dietFood.quantity) / 100) : 0}g
                                                                                                        </div>
                                                                                                        <small className="text-muted">Grasas</small>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    ))}
                                                                                </div>
                                                                            )}
                                                                        </CardBody>
                                                                    </Card>
                                                                ))}
                                                                
                                                                {['Desayuno', 'Media Mañana', 'Almuerzo', 'Merienda', 'Cena'].map((mealType) => {
                                                                    const mealExists = getTodayFoodsByMeal().some(meal => meal.mealType === mealType);
                                                                    if (!mealExists) {
                                                                        return (
                                                                            <Card key={mealType} className="mb-3 border-0 shadow-sm">
                                                                                <CardBody className="p-0">
                                                                                    <div 
                                                                                        className="bg-light text-muted p-3 rounded"
                                                                                        style={{ cursor: 'pointer' }}
                                                                                        onClick={() => toggleDietAccordion(mealType)}
                                                                                    >
                                                                                        <h6 className="mb-0 fw-bold d-flex align-items-center justify-content-between">
                                                                                            <span>
                                                                                                <i className="bi bi-clock me-2"></i>
                                                                                                {mealType}
                                                                                            </span>
                                                                                            <div className="d-flex align-items-center">
                                                                                                <span className="badge bg-warning text-dark me-2">Ayuno</span>
                                                                                                <i className={`bi bi-chevron-${openDietAccordions.includes(mealType) ? 'up' : 'down'}`}></i>
                                                                                            </div>
                                                                                        </h6>
                                                                                    </div>
                                                                                    
                                                                                    {openDietAccordions.includes(mealType) && (
                                                                                        <div className="text-center py-4 text-muted">
                                                                                            <i className="bi bi-ban fs-1 mb-2"></i>
                                                                                            <p className="mb-0">No hay alimentos programados para {mealType.toLowerCase()}</p>
                                                                                        </div>
                                                                                    )}
                                                                                </CardBody>
                                                                            </Card>
                                                                        );
                                                                    }
                                                                    return null;
                                                                })}
                                                            </>
                                                        ) : (
                                                            <div className="text-center py-5">
                                                                <i className="bi bi-calendar-x display-1 text-muted mb-3"></i>
                                                                <h6 className="text-muted">
                                                                    No hay alimentos programados para hoy
                                                                </h6>
                                                            </div>
                                                        )}
                                                    </div>

                                                    {getTodayFoodsByMeal().length > 0 && (
                                                        <div className="mt-4">
                                                            <Card className="border-0 shadow-sm">
                                                                <CardBody className="p-4">
                                                                    <h6 className="mb-4 text-center fw-bold">
                                                                        📊 Resumen nutricional del día
                                                                    </h6>
                                                                    <div className="row g-3 text-center">
                                                                        <div className="col-6 col-md-3">
                                                                            <div className="p-3 bg-dark text-white rounded-3 shadow">
                                                                                <div className="fw-bold fs-3 mb-1">
                                                                                    {getTodayFoodsByMeal().reduce((total, meal) => 
                                                                                        total + meal.foods.reduce((sum, food) => 
                                                                                            sum + Math.round((food.food.calories * food.quantity) / 100), 0
                                                                                        ), 0
                                                                                    )}
                                                                                </div>
                                                                                <small className="text-light">Calorías</small>
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-6 col-md-3">
                                                                            <div className="p-3 text-white rounded-3 shadow" style={{backgroundColor: '#e74c3c'}}>
                                                                                <div className="fw-bold fs-3 mb-1">
                                                                                    {getTodayFoodsByMeal().reduce((total, meal) => 
                                                                                        total + meal.foods.reduce((sum, food) => 
                                                                                            sum + Math.round((food.food.proteins || 0) * food.quantity / 100), 0
                                                                                        ), 0
                                                                                    )}g
                                                                                </div>
                                                                                <small className="text-light">Proteínas</small>
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-6 col-md-3">
                                                                            <div className="p-3 text-dark rounded-3 shadow" style={{backgroundColor: '#f39c12'}}>
                                                                                <div className="fw-bold fs-3 mb-1">
                                                                                    {getTodayFoodsByMeal().reduce((total, meal) => 
                                                                                        total + meal.foods.reduce((sum, food) => 
                                                                                            sum + Math.round((food.food.carbs || 0) * food.quantity / 100), 0
                                                                                        ), 0
                                                                                    )}g
                                                                                </div>
                                                                                <small className="text-dark">Carbohidratos</small>
                                                                            </div>
                                                                        </div>
                                                                        <div className="col-6 col-md-3">
                                                                            <div className="p-3 text-white rounded-3 shadow" style={{backgroundColor: '#9b59b6'}}>
                                                                                <div className="fw-bold fs-3 mb-1">
                                                                                    {getTodayFoodsByMeal().reduce((total, meal) => 
                                                                                        total + meal.foods.reduce((sum, food) => 
                                                                                            sum + Math.round((food.food.fats || 0) * food.quantity / 100), 0
                                                                                        ), 0
                                                                                    )}g
                                                                                </div>
                                                                                <small className="text-light">Grasas</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="mt-4 text-center">
                                                                        <div className="badge bg-secondary fs-6 px-3 py-2">
                                                                            Total de alimentos: {getTodayFoodsByMeal().reduce((total, meal) => 
                                                                                total + meal.foods.length, 0
                                                                            )}
                                                                        </div>
                                                                    </div>
                                                                </CardBody>
                                                            </Card>
                                                        </div>
                                                    )}
                                                </div>
                                            ) : (
                                                <div className="text-center py-5">
                                                    <i className="bi bi-exclamation-triangle display-1 text-warning mb-3"></i>
                                                    <h6 className="text-muted">No tienes una dieta activa</h6>
                                                    <p className="text-muted">
                                                        Activa una dieta desde la lista para ver los alimentos recomendados.
                                                    </p>
                                                    <Button color="primary" isLight onClick={() => navigate("/user-diets")}>
                                                        Ver Dietas
                                                    </Button>
                                                </div>
                                            )}
                                        </div>
                                    )}
                                </CardBody>
                            </Card>
                        </div>
                    </div>
                </div>
            </Page>
        </Fragment>
    );
};

export default DailyIntakePage;