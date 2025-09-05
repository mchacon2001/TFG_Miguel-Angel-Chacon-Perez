import {
  FC,
  useCallback,
  useEffect,
  useState,
} from "react";
import { getIn, useFormik } from "formik";
import * as yup from "yup";
import {
  CardBody,
  CardTitle,
  CardFooter,
} from "../../components/bootstrap/Card";
import Button from "../../components/bootstrap/Button";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Spinner from "../../components/bootstrap/Spinner";
import SearchableSelect from "../../components/SearchableSelect";
import useFetch from "../../hooks/useFetch";
import useFilters from "../../hooks/useFilters";
import { DietService } from "../../services/diets/dietService";
import { toast } from "react-toastify";
import { FoodService } from "../../services/foods/foodService";
import { AuthState } from "../../redux/authSlice";
import { useSelector } from "react-redux";
import { RootState } from "../../redux/store";
import { UserService } from "../../services/users/userService";
import { UserApiResponse } from "../../type/user-type";

interface DietFormProps {
  isLoading: boolean;
  submit: (values: any) => void;
  data?: any;
}

const DAYS_OF_WEEK = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
const MEALS = ["Desayuno", "Media Mañana", "Almuerzo", "Merienda", "Cena"];

const getInitialDays = (data?: any) => {
  if (data?.dietFood?.length === 7) {
    return data.dietFood.map((day: any, i: number) => ({
      day: DAYS_OF_WEEK[i],
      meals: MEALS.map((meal) => ({
        name: meal,
        foods: day.meals?.find((m: any) => m.name === meal)?.foods || [],
      })),
    }));
  }
  return DAYS_OF_WEEK.map((day) => ({
    day,
    meals: MEALS.map((meal) => ({ name: meal, foods: [] })),
  }));
};

const dietSchema = yup.object({
  name: yup.string().min(1, "Demasiado corto").max(100, "Demasiado largo").required("El nombre es obligatorio"),
  description: yup.string().min(1, "Demasiado corto").max(1000, "Demasiado largo"),

  goal: yup
    .number()
    .typeError("Debe ser un número")
    .min(0, "Debe ser mayor o igual a 0")
    .required("El objetivo diario es obligatorio"),
  // Only keep the three main flags
  toGainMuscle: yup.boolean(),
  toLoseWeight: yup.boolean(),
  toMaintainWeight: yup.boolean(),
  dietFood: yup.array().of(
    yup.object({
      day: yup.string().required(),
      meals: yup.array().of(
        yup.object({
          name: yup.string().required(),
          foods: yup.array().of(
            yup.object({
              foodId: yup.string().when([], {
                is: (_: any, schema: any) => true,
                then: (schema) =>
                  schema.test(
                    "required-if-exists",
                    "Alimento obligatorio",
                    function (value) {
                      return value !== undefined && value !== "";
                    }
                  ),
              }),
              quantity: yup
                .number()
                .transform((value, originalValue) =>
                  String(originalValue).trim() === "" ? undefined : value
                )
                .when("foodId", {
                  is: (foodId: string) => !!foodId,
                  then: (schema) =>
                    schema
                      .typeError("Cantidad obligatoria")
                      .min(1, "Debe ser mayor a 0")
                      .required("Cantidad obligatoria"),
                  otherwise: (schema) => schema.notRequired(),
                }),
            })
          )
        })
      )
    })
  )
});

const DietForm: FC<DietFormProps> = ({ isLoading, submit, data }) => {
  const mode = data ? "Editar" : "Crear";
  const [days, setDays] = useState<any[]>(getInitialDays(data));
  const [caloriesByDay, setCaloriesByDay] = useState<number[]>(Array(7).fill(0));
  const [macrosByDay, setMacrosByDay] = useState<{protein: number, carbs: number, fat: number}[]>(Array(7).fill({protein: 0, carbs: 0, fat: 0}));
  const { user }: AuthState = useSelector((state: RootState) => state.auth);
  const isAdmin = user?.roles.includes('Administrador') || user?.roles.includes('Superadministrador');

  const getInitialOpenDays = () => {
    if (!data || !data.dietFood) return [];
    return data.dietFood
      .map((day: any, idx: number) =>
        day.meals.some((meal: any) => Array.isArray(meal.foods) && meal.foods.length > 0) ? idx : null
      )
      .filter((idx: number | null) => idx !== null);
  };
  const [openDays, setOpenDays] = useState<number[]>(mode === "Editar" ? getInitialOpenDays() : []);

  const { filters } = useFilters({}, [], 1, 1000);

    const [users] = useFetch(
      useCallback(async () => {
        if (!isAdmin) {
          return { users: [{ id: user?.id, name: user?.name }] }; // Return current user if not admin
        }
        let auxfilters = { ...filters };
        auxfilters.limit = 99999;
  
        const response = await new UserService().getUsers(auxfilters);
        return response.getResponseData() as UserApiResponse;
      }, [filters])
    );
    
  const getUsersList = () => {
    if (!users || !users.users) return [];
    return users.users
      .filter((user: any) => 
        !user.userRoles?.some((role: any) => role.role.id === 1 || role.role.id === 2)
      )
      .map((user: any) => ({
        value: user.id,
        label: `${user.name}`,
      }));
  };
  const formik = useFormik({
    initialValues: {
      name: data?.name || "",
      description: data?.description || "",
      userId: isAdmin ? '' : user?.id,
      goal: data?.goal || "",
      // Only keep the three main flags
      toGainMuscle: data?.toGainMuscle || false,
      toLoseWeight: data?.toLoseWeight || false,
      toMaintainWeight: data?.toMaintainWeight || false,
      dietFood: getInitialDays(data),
    },
    validationSchema: dietSchema,
    validateOnMount: false,
    validateOnBlur: false,
    validateOnChange: true,
    onSubmit: (values) => {
      const cleanedDietFood = values.dietFood.map((day: any) => ({
        ...day,
        meals: day.meals.map((meal: any) => ({
          ...meal,
          foods: meal.foods.filter((food: any) => food.foodId && food.foodId !== "")
        }))
      }));
      submit({ ...values, dietFood: cleanedDietFood });
    },
    enableReinitialize: true,
  });

const verifyClass = (fieldPath: string) =>
  getIn(formik.touched, fieldPath) && getIn(formik.errors, fieldPath)
    ? "is-invalid"
    : "";

const ERROR_MIN_HEIGHT = 16;

const showErrors = (fieldPath: string): JSX.Element => {
  const error = getIn(formik.errors, fieldPath);
  const show = formik.submitCount > 0 && !!error;

  let content: React.ReactNode = null;
  if (show) {
    if (typeof error === "string") {
      content = error;
    } else if (Array.isArray(error)) {
      content = error.filter(Boolean).map((err, idx) =>
        typeof err === "string" ? <div key={idx}>{err}</div> : null
      );
    } else if (typeof error === "object" && error !== null) {
      content = Object.values(error)
        .filter((err): err is string => typeof err === "string")
        .map((err, idx) => <div key={idx}>{err}</div>);
    }
  }
  return (
    <div
      className="invalid-feedback"
      style={{ minHeight: ERROR_MIN_HEIGHT, lineHeight: '16px', display: 'block', overflow: 'hidden', maxWidth: '100%' }}
    >
      {content || <>&nbsp;</>}
    </div>
  );
};



  useEffect(() => {
    setDays(getInitialDays(data));
    if (mode === "Editar") {
      setOpenDays(getInitialOpenDays());
    }
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [data]);

  

  const [foodsApiData, foodsLoading, foodsError] = useFetch(
    useCallback(async () => {
      const response = await new FoodService().getFood(filters);
      return response.getResponseData();
    }, [filters])
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

  useEffect(() => {
    const newCaloriesByDay = formik.values.dietFood.map((day: any) => {
      return day.meals.reduce((dayTotal: number, meal: any) => {
        const mealTotal = meal.foods.reduce((mealSum: number, food: any) => {
          const foodData = foodsData.find((f: any) => f.id === food.foodId);
          if (!foodData) return mealSum;
          const caloriesPerGram = foodData.calories / 100;
          return mealSum + caloriesPerGram * food.quantity;
        }, 0);
        return dayTotal + mealTotal;
      }, 0);
    });
    setCaloriesByDay(newCaloriesByDay);

    const newMacrosByDay = formik.values.dietFood.map((day: any) => {
      let protein = 0, carbs = 0, fat = 0;
      day.meals.forEach((meal: any) => {
        meal.foods.forEach((food: any) => {
          const foodData = foodsData.find((f: any) => f.id === food.foodId);
          if (!foodData) return;
          protein += (foodData.proteins ?? 0) / 100 * food.quantity;
          carbs += (foodData.carbs ?? 0) / 100 * food.quantity;
          fat += (foodData.fats ?? 0) / 100 * food.quantity;
        });
      });
      return {
        protein: Math.round(protein),
        carbs: Math.round(carbs),
        fat: Math.round(fat),
      };
    });
    setMacrosByDay(newMacrosByDay);
  }, [formik.values.dietFood, foodsData]);


  const addFood = (dayIndex: number, mealIndex: number) => {
    const updatedDays = [...formik.values.dietFood];
    updatedDays[dayIndex].meals[mealIndex].foods.push({
      foodId: "",
      quantity: 0,
      foodCategoryId: "",
    });
    formik.setFieldValue("dietFood", updatedDays);
  };


  const updateFood = (dayIndex: number, mealIndex: number, foodIndex: number, field: string, value: any) => {
    if (field === "quantity") {
      if (value === "" || value === null) {
        value = "";
      } else {
        const parsed = parseFloat(value);
        value = isNaN(parsed) ? value : parsed;
      }
    }
    const updatedDays = [...formik.values.dietFood];
    updatedDays[dayIndex].meals[mealIndex].foods[foodIndex][field] = value;
    formik.setFieldValue("dietFood", updatedDays);
  };

  const removeFood = (dayIndex: number, mealIndex: number, foodIndex: number) => {
    const updatedDays = [...formik.values.dietFood];
    updatedDays[dayIndex].meals[mealIndex].foods.splice(foodIndex, 1);
    formik.setFieldValue("dietFood", updatedDays);
  };

  

  return (
    <form onSubmit={formik.handleSubmit}>
      <CardBody>
        <div className="d-flex align-items-end gap-3 mb-3">
          <FormGroup label="Nombre" className="mb-0 flex-grow-1">
            <Input
              id="name"
              name="name"
              type="text"
              value={formik.values.name}
              onChange={formik.handleChange}
              onBlur={formik.handleBlur}
              className={verifyClass("name")}
            />
            {showErrors("name")}
          </FormGroup>
            {isAdmin && mode === 'Crear' && (
              <FormGroup requiredInputLabel label="Usuario" className="col-md-6">
                <SearchableSelect
                  isSearchable
                  name="userId"
                  value={
                    getUsersList().find(
                      (opt: { value: number | string; label: string }) =>
                        opt.value === formik.values.userId
                    ) || null
                  }
                  options={getUsersList()}
                  classname={verifyClass("userId")}
                  placeholder="Selecciona un usuario"
                  onChange={(e: { value: number | string; label: string }) => {
                    formik.setFieldValue("userId", e.value);
                  }}
                  onBlur={() => formik.setFieldTouched('userId', true)}
                />
                {showErrors("userId")}
              </FormGroup>
            )}
          <FormGroup label="Objetivo diario (kcal)" className="mb-0" style={{ width: 200 }}>
            <Input
              id="goal"
              name="goal"
              type="text"
              min={0}
              value={formik.values.goal || ''}
              onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                const value = e.target.value.replace(/[^0-9]/g, '');
                formik.setFieldValue('goal', value);
              }}
              onBlur={formik.handleBlur}
              className={verifyClass("goal")}
            />
            {showErrors("goal")}
          </FormGroup>
        </div>

        <FormGroup label="Descripción" className="mb-3">
          <Input
            id="description"
            name="description"
            value={formik.values.description}
            onChange={formik.handleChange}
            onBlur={formik.handleBlur}
            className={verifyClass("description")}
          />
          {showErrors("description")}
        </FormGroup>

        {/* Add flag checkboxes for administrators */}
        {isAdmin && (
          <div className="mb-4">
            <h5 className="mb-3">Objetivos de la dieta (se asignará automáticamente a usuarios con estos objetivos)</h5>
            <div className="row">
              <div className="col-md-4">
                <div className="form-check mb-2">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    id="toGainMuscle"
                    name="toGainMuscle"
                    checked={formik.values.toGainMuscle}
                    onChange={formik.handleChange}
                  />
                  <label className="form-check-label" htmlFor="toGainMuscle">
                    Ganar músculo
                  </label>
                </div>
                <div className="form-check mb-2">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    id="toLoseWeight"
                    name="toLoseWeight"
                    checked={formik.values.toLoseWeight}
                    onChange={formik.handleChange}
                  />
                  <label className="form-check-label" htmlFor="toLoseWeight">
                    Perder peso
                  </label>
                </div>
                <div className="form-check mb-2">
                  <input
                    className="form-check-input"
                    type="checkbox"
                    id="toMaintainWeight"
                    name="toMaintainWeight"
                    checked={formik.values.toMaintainWeight}
                    onChange={formik.handleChange}
                  />
                  <label className="form-check-label" htmlFor="toMaintainWeight">
                    Mantener peso
                  </label>
                </div>
              </div>
            </div>
          </div>
        )}

        <div className="accordion" id="dietAccordion">
          {formik.values.dietFood.map((day: any, dayIndex: any) => (
            <div className="accordion-item mb-2" key={dayIndex}>
              <h2 className="accordion-header" id={`heading${dayIndex}`}>
                <button
                  className={`accordion-button mt-4 ${openDays.includes(dayIndex) ? '' : 'collapsed'}`}
                  type="button"
                  onClick={() => {
                    setOpenDays((prev) =>
                      prev.includes(dayIndex)
                        ? prev.filter((idx) => idx !== dayIndex)
                        : [...prev, dayIndex]
                    );
                  }}
                  aria-expanded={openDays.includes(dayIndex)}
                  aria-controls={`collapse${dayIndex}`}
                  style={{
                    fontWeight: 600,
                    borderRadius: openDays.includes(dayIndex) ? "1.5rem" : "",
                    backgroundColor: openDays.includes(dayIndex) ? "#ffbb00" : "",
                    color: openDays.includes(dayIndex) ? "#fff" : "",
                    transition: "background 0.2s, border-radius 0.2s, color 0.2s"
                  }}
                >
                  {DAYS_OF_WEEK[dayIndex]}
                   <span
                className="ms-2"
                style={{
                  fontSize: 14,
                  color:
                    caloriesByDay[dayIndex] > Number(formik.values.goal)
                      ? "red"
                      : openDays.includes(dayIndex)
                        ? "#fff"
                        : "#000",
                  fontWeight:
                    caloriesByDay[dayIndex] > Number(formik.values.goal)
                      ? "bold"
                      : "normal",
                }}
              >
                {caloriesByDay[dayIndex]
                  ? `Total: ${Math.round(caloriesByDay[dayIndex])} kcal`
                  : ""}
              </span>
                  <span
                    className="ms-3"
                    style={{
                      fontSize: 13,
                      color: openDays.includes(dayIndex) ? "#fff" : "#000"
                    }}
                  >
                    {macrosByDay[dayIndex] &&
                      `Proteínas: ${macrosByDay[dayIndex].protein}g | Carbs: ${macrosByDay[dayIndex].carbs}g | Grasas: ${macrosByDay[dayIndex].fat}g`}
                  </span>
                </button>
              </h2>
              <div
                id={`collapse${dayIndex}`}
                className={`accordion-collapse collapse${openDays.includes(dayIndex) ? ' show' : ''}`}
                aria-labelledby={`heading${dayIndex}`}
              >
                <div className="accordion-body p-0">
                  <div className="mb-4 border-0 rounded-0 p-3">
                    {day.meals.map((meal: any, mealIndex: number) => (
                      <div key={mealIndex} className="mb-3">
                        <div className="d-flex justify-content-between align-items-center mb-1">
                          <strong>{meal.name}</strong>
                          <Button type="button" size="sm" onClick={() => addFood(dayIndex, mealIndex)}>
                            Añadir alimento
                          </Button>
                        </div>
                        {meal.foods.map((food: any, foodIndex: number) => {
                          const basePath = `dietFood[${dayIndex}].meals[${mealIndex}].foods[${foodIndex}]`;
                          return (
                            <div
                              key={foodIndex}
                              className="d-flex flex-column flex-md-row align-items-stretch align-items-md-start mb-2 gap-2 flex-nowrap"
                              style={{ width: '100%' }}
                            >

                              <div className="flex-grow-1" style={{ minWidth: 200 }}>
                                <SearchableSelect
                                  placeholder="Selecciona alimento"
                                  value={foodOptions.find((option : any) => option.value === food.foodId) || ''}
                                  onChange={(option: any) =>
                                    updateFood(dayIndex, mealIndex, foodIndex, 'foodId', option?.value || '')
                                  }
                                  options={foodOptions}
                                  name={`${basePath}.foodId`}
                                  classname={verifyClass(`${basePath}.foodId`)}
                                />
                                <div>
                                  {showErrors(`${basePath}.foodId`)}
                                </div>
                              </div>
                              <div>
                                <div className="input-group">
                                  <Input
                                    type="text"
                                    placeholder="Cantidad"
                                    value={food.quantity === 0 ? "" : food.quantity}
                                    min={0}
                                    inputMode="decimal"
                                    onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                                      const val = e.target.value;
                                      if (/^\d*(\.?\d*)?$/.test(val)) {
                                        updateFood(dayIndex, mealIndex, foodIndex, "quantity", val);
                                      }
                                    }}
                                    onBlur={formik.handleBlur}
                                    className={verifyClass(`${basePath}.quantity`)}
                                    name={`${basePath}.quantity`}
                                    style={{ maxWidth: '100%' }}
                                  />
                                  <span className="input-group-text">g</span>
                                </div>
                                <div>
                                  {showErrors(`${basePath}.quantity`)}
                                </div>
                              </div>
                              <div className="d-flex align-items-end" style={{ height: '100%' }}>
                                <Button
                                  type="button"
                                  size="sm"
                                  color="danger"
                                  className="mb-2"
                                  onClick={() => removeFood(dayIndex, mealIndex, foodIndex)}
                                  style={{ whiteSpace: 'nowrap', height: 38, marginTop: 0 }}
                                >
                                  Eliminar
                                </Button>
                              </div>
                            </div>
                          );
                        })}
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </CardBody>
      <CardFooter className="d-flex justify-content-center">
        <Button type="submit" size="lg" color="primary">
          {isLoading ? <Spinner isSmall /> : `${mode} Dieta`}
        </Button>
      </CardFooter>
    </form>
  );
};

export default DietForm;
