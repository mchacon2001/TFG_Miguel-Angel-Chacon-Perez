import { FC, Fragment, useCallback, useContext, useEffect, useState } from 'react';
import { getIn, useFormik } from 'formik';
import * as yup from 'yup';
import { PrivilegeContext } from '../../components/priviledge/PriviledgeProvider';
import { CardBody, CardTitle, CardFooter } from '../../components/bootstrap/Card';
import Button from '../../components/bootstrap/Button';
import FormGroup from '../../components/bootstrap/forms/FormGroup';
import Input from '../../components/bootstrap/forms/Input';
import Spinner from '../../components/bootstrap/Spinner';
import SearchableSelect from '../../components/SearchableSelect';
import useFetch from '../../hooks/useFetch';
import useFilters from '../../hooks/useFilters';
import useRoutineCategories from '../../hooks/useRoutineCategories';
import { RoutineService } from '../../services/routines/routineService';
import { ExerciseCategoriesApiResponse } from '../../type/exercise-type';
import { toast } from 'react-toastify';
import { ExerciseService } from '../../services/exercises/exerciseService';
import useExercisesCategories from '../../hooks/useExercisesCategories';
import { AuthState } from '../../redux/authSlice';
import { useSelector } from 'react-redux';
import { RootState } from '../../redux/store';
import { UserService } from '../../services/users/userService';
import { UserApiResponse } from '../../type/user-type';


interface RoutineFormProps {
  isLoading: boolean;
  submit: (values: any) => void;
  data?: any;
}

const routineSchema = yup.object({
  routineCategoryId: yup.string().required('La categoría de rutina es obligatoria'),
  name: yup.string().min(1, 'Demasiado corto').max(100, 'Demasiado largo').required('El nombre es obligatorio'),
  description: yup.string().min(1, 'Demasiado corto').max(1000, 'Demasiado largo'),
});


const RoutineForm: FC<RoutineFormProps> = ({ isLoading, submit, data }) => {
  const { getRoutinesCategoriesList } = useRoutineCategories();
  const { getExercisesCategoriesList } = useExercisesCategories();
  const { filters } = useFilters({}, [], 1, 1000);
  const mode = data ? 'Editar' : 'Crear';

  const { user }: AuthState = useSelector((state: RootState) => state.auth);
  
  const isAdmin = user?.roles.includes('Administrador') || user?.roles.includes('Superadministrador');

  const [days, setDays] = useState<any[]>([]);
  const [defaultCategory, setDefaultCategory] = useState<{ value: string; label: string } | null>(null);

  const [categoryRoutinesData] = useFetch(
    useCallback(async () => {
      const response = await new RoutineService().getRoutineCategories(filters);
      return response.getResponseData() as ExerciseCategoriesApiResponse;
    }, [filters])
  );

  const [categoryExercisesData] = useFetch(
    useCallback(async () => {
      const response = await new ExerciseService().getExercisesCategories(filters);
      return response.getResponseData() as ExerciseCategoriesApiResponse;
    }, [filters])
  );

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
        routineCategoryId: data?.routineCategoryId || '',
        name: data?.name || '',
        description: data?.description || '',
        userId: isAdmin ? (data?.userId || '') : user?.id,
        // Add flag fields with proper defaults
        toGainMuscle: data?.toGainMuscle ?? false,
        toLoseWeight: data?.toLoseWeight ?? false,
        toMaintainWeight: data?.toMaintainWeight ?? false,
        toImprovePhysicalHealth: data?.toImprovePhysicalHealth ?? false,
        toImproveMentalHealth: data?.toImproveMentalHealth ?? false,
        fixShoulder: data?.fixShoulder ?? false,
        fixKnees: data?.fixKnees ?? false,
        fixBack: data?.fixBack ?? false,
        rehab: data?.rehab ?? false,
        routineExercises: data?.routineExercises?.map((day: any) => ({
            day: day.dayNumber || day.day,
            exercises: day.exercises.map((exercise: any) => ({
                exerciseCategoryId: exercise.exerciseCategoryId || '',
                exerciseId: exercise.exerciseId || '',
                sets: exercise.sets || 1,
                reps: exercise.reps || 1,
                restTime: exercise.restTime || 0,
            }))
        })) || [{ day: 1, exercises: [{
            exerciseCategoryId: '',
            exerciseId: '',
            sets: 4,
            reps: 8,
            restTime: 60
        }] }]
    },
    validationSchema: routineSchema,
    onSubmit: (values) => {
      const hasAtLeastOneExercise = days.some((day) => day.exercises.length > 0);
      const hasEmptyDay = days.some((day) => day.exercises.length === 0);
      const hasInvalidExercise = days.some((day) =>
        day.exercises.some((ex : any) => ex.sets === 0 && ex.reps === 0)
      );

      if (!hasAtLeastOneExercise) {
        toast.error('La rutina debe tener al menos un ejercicio.');
        return;
      }
      if (hasEmptyDay) {
        toast.warning('Hay días sin ejercicios. Por favor revisa antes de guardar.');
        return;
      }
      if (hasInvalidExercise) {
        toast.error('Los ejercicios no pueden tener sets y reps en 0.');
        return;
      }
  
      const finalData = {
        ...values,
        routineExercises: days,
      };
      submit(finalData);
    }
  });

  const verifyClass = (fieldPath: string) => {
    return getIn(formik.touched, fieldPath) && getIn(formik.errors, fieldPath) ? 'is-invalid' : '';
  };

  const showErrors = (inputFieldID: keyof typeof formik.values) => {
    // @ts-ignore
    return (formik.touched[inputFieldID] && formik.errors[inputFieldID]) ? (<div className="invalid-feedback">{formik.errors[inputFieldID]}</div>
    ) : <></>;
  };

  const showNestErrors = (fieldPath: string) => {
    const error = getIn(formik.errors, fieldPath);
    const touched = getIn(formik.touched, fieldPath);
    return touched && error ? <div className="invalid-feedback">{error}</div> : null;
  };

  useEffect(() => {
    if (data?.routineExercises?.length > 0 && categoryExercisesData?.exerciseCategories) {
      const enrichedDays = data.routineExercises.map((day: any) => {
        const enrichedExercises = day.exercises.map((exercise: any) => {
          const foundCategory = categoryExercisesData.exerciseCategories.find((cat: any) =>
            cat.exercises.some((ex: any) => ex.id === exercise.exerciseId)
          );
          return {
            ...exercise,
            exerciseCategoryId: foundCategory?.id || '',
          };
        });
        return {
          ...day,
          exercises: enrichedExercises,
        };
      });

      setDays(enrichedDays);
    } else if (!data) {
      setDays([{ day: 1, exercises: [] }]);
    }
  }, [data, categoryExercisesData]);

/*   useEffect(() => {
    if (data?.routineCategoryId && categoryRoutinesData?.routines) {
      const foundRoutineCategory = categoryRoutinesData.routines.find((category: any) => category.id === data.routineCategoryId);
      if (foundRoutineCategory) {
        setDefaultCategory({ value: foundRoutineCategory.id, label: foundRoutineCategory.name });
      }
    }
  }, [data?.routineCategoryId, categoryRoutinesData]); */

  const addDay = () => {
    const nextDayNumber = days.length > 0 ? Math.max(...days.map((d) => d.day)) + 1 : 1;
    setDays([...days, { day: nextDayNumber, exercises: [{
      exerciseCategoryId: '',
      exerciseId: '',
      sets: 4,
      reps: 8,
      restTime: 60
    }] }]);
  };

  const addExercise = (dayIndex: number) => {
    const updatedDays = [...days];
    updatedDays[dayIndex].exercises.push({
      exerciseId: '',
      sets: 4,
      reps: 8,
      restTime: 60,
      exerciseCategoryId: '',
    });
    setDays(updatedDays);
  };

  const updateExercise = (dayIndex: number, exerciseIndex: number, field: string, value: any) => {
    if (value < 0) {
      return; 
    }
  
    const updatedDays = [...days];
    updatedDays[dayIndex].exercises[exerciseIndex][field] = value;
    setDays(updatedDays);
  };
  

  const removeExercise = (dayIndex: number, exerciseIndex: number) => {
    const updatedDays = [...days];
    updatedDays[dayIndex].exercises.splice(exerciseIndex, 1);
    setDays(updatedDays);
  };

  const removeDay = (dayIndex: number) => {
    const updatedDays = [...days];
    updatedDays.splice(dayIndex, 1);
    setDays(updatedDays);
  };

  return (
    <Fragment>
      <form onSubmit={formik.handleSubmit} autoComplete="off">
        <CardBody isScrollable={false} className="row g-3 p-5">
          <CardTitle>Información general</CardTitle>

          <FormGroup requiredInputLabel label="Nombre" className="col-md-6">
            <Input
              id="name"
              onChange={formik.handleChange}
              onBlur={formik.handleBlur}
              value={formik.values.name}
              className={verifyClass("name")}
            />
            {showErrors("name")}
          </FormGroup>

          <FormGroup requiredInputLabel label="Categoría" className="col-md-6">
            <SearchableSelect
              isSearchable
              name="routineCategoryId"
              value={
                getRoutinesCategoriesList().find(
                  (opt: { value: number | string; label: string }) =>
                    opt.value === formik.values.routineCategoryId
                ) || defaultCategory
              }
              options={getRoutinesCategoriesList()}
              classname={verifyClass("routineCategoryId")}
              placeholder="Selecciona una categoría"
              onChange={(e: { value: number | string; label: string }) => {
                formik.setFieldValue("routineCategoryId", e.value);
              }}
              onBlur={() => formik.setFieldTouched('routineCategoryId', true)}
            />
            {showErrors("routineCategoryId")} 
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

          {isAdmin && (
            <>
              <CardTitle className="my-4">Objetivos</CardTitle>
              <div className="row">
                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="toGainMuscle"
                      checked={formik.values.toGainMuscle}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="toGainMuscle">
                      Ganar músculo
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="toLoseWeight"
                      checked={formik.values.toLoseWeight}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="toLoseWeight">
                      Perder peso
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="toMaintainWeight"
                      checked={formik.values.toMaintainWeight}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="toMaintainWeight">
                      Mantener peso
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="toImprovePhysicalHealth"
                      checked={formik.values.toImprovePhysicalHealth}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="toImprovePhysicalHealth">
                      Mejorar salud física
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="toImproveMentalHealth"
                      checked={formik.values.toImproveMentalHealth}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="toImproveMentalHealth">
                      Mejorar salud mental
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="fixShoulder"
                      checked={formik.values.fixShoulder}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="fixShoulder">
                      Arreglar hombro
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="fixKnees"
                      checked={formik.values.fixKnees}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="fixKnees">
                      Arreglar rodillas
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="fixBack"
                      checked={formik.values.fixBack}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="fixBack">
                      Arreglar espalda
                    </label>
                  </div>
                </FormGroup>

                <FormGroup className="col-md-3">
                  <div className="form-check form-switch">
                    <input
                      className="form-check-input"
                      type="checkbox"
                      id="rehab"
                      checked={formik.values.rehab}
                      onChange={formik.handleChange}
                    />
                    <label className="form-check-label" htmlFor="rehab">
                      Rehabilitación
                    </label>
                  </div>
                </FormGroup>
              </div>
            </>
          )}

          <FormGroup label="Descripción" className="col-md-12">
            <textarea
              id="description"
              onChange={formik.handleChange}
              onBlur={formik.handleBlur}
              value={formik.values.description}
              rows={3}
              className={`form-control ${verifyClass("description")}`}
            />
            {showErrors("description")}
          </FormGroup>

          <CardTitle className="mt-4">Ejercicios</CardTitle>

          {days.map((day, dayIndex) => (
            <div key={day.day} className="day-card">
              <Button
                type="button"
                color="danger"
                size="sm"
                className="position-absolute top-0 end-0 m-2"
                onClick={() => removeDay(dayIndex)}
              >
                Eliminar Día
              </Button>

              <h5 className="mb-4">Día {day.day}</h5>

              {day.exercises.map((exercise: any, exerciseIndex: number) => (
                <div
                  key={exerciseIndex}
                  className="exercise-card"
                  style={{
                    border: '1px solid #e0e0e0',
                    borderRadius: '12px',
                    padding: '24px 20px',
                    marginBottom: '20px',
                    background: '#fafbfc',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.04)',
                    position: 'relative',
                  }}
                >
                  <Button
                    type="button"
                    onClick={() => removeExercise(dayIndex, exerciseIndex)}
                    className="delete-exercise-btn"
                    style={{
                      position: 'absolute',
                      top: '12px',
                      right: '12px',
                      background: 'transparent',
                      border: 'none',
                      fontSize: '1.2rem',
                      color: '#d32f2f',
                      zIndex: 2,
                    }}
                  >
                    🗑️
                  </Button>
                  <div className="row g-3 align-items-end">
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Tipo de ejercicio</label>
                      <SearchableSelect
                        isSearchable
                        name="exerciseCategoryId"
                        classname={verifyClass(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].exerciseCategoryId`)}
                        options={(categoryExercisesData?.exerciseCategories || []).map((cat: any) => ({
                          value: cat.id,
                          label: cat.name,
                        }))}
                        onChange={(e: any) => {
                          updateExercise(dayIndex, exerciseIndex, "exerciseCategoryId", e.value);
                          updateExercise(dayIndex, exerciseIndex, "exerciseId", ""); 
                        }}
                        value={(categoryExercisesData?.exerciseCategories || [])
                          .map((cat: any) => ({ value: cat.id, label: cat.name }))
                          .find((option: any) => option.value === exercise.exerciseCategoryId) || null
                        }
                        placeholder="Tipo ejercicio"
                      />
                      {showNestErrors(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].exerciseCategoryId`)}
                    </div>
                    <div className="col-md-4">
                      <label className="form-label fw-semibold">Ejercicio</label>
                      <SearchableSelect
                        isSearchable
                        name="exerciseId"
                        classname={verifyClass(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].exerciseId`)}
                        options={
                          (categoryExercisesData?.exerciseCategories || [])
                            .find((cat: any) => cat.id === exercise.exerciseCategoryId)
                            ?.exercises.map((ex: any) => ({
                              value: ex.id,
                              label: ex.name,
                            })) || []
                        }
                        onChange={(e: any) => {
                          updateExercise(dayIndex, exerciseIndex, "exerciseId", e.value);
                        }}
                        value={
                          ((categoryExercisesData?.exerciseCategories || [])
                            .find((cat: any) => cat.id === exercise.exerciseCategoryId)
                            ?.exercises.map((ex: any) => ({
                              value: ex.id,
                              label: ex.name,
                            })) || []
                          ).find((option: any) => option.value === exercise.exerciseId) || null
                        }
                        placeholder="Ejercicio"
                      />
                      {showNestErrors(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].exerciseId`)}
                    </div>
                    <div className="col-md-1">
                      <label className="form-label fw-semibold">Sets</label>
                      <Input
                        id={`sets-${dayIndex}-${exerciseIndex}`}
                        type="text"
                        inputMode="numeric"
                        value={exercise.sets}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                          const value = e.target.value.replace(/[^0-9]/g, '');
                          updateExercise(dayIndex, exerciseIndex, "sets", value === '' ? '' : Number(value));
                        }}
                        placeholder="Sets"
                        style={{
                          borderRadius: '8px',
                          border: '1px solid #bdbdbd',
                          padding: '6px 10px',
                          width: '100%',
                        }}
                      />
                      {showNestErrors(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].sets`)}
                    </div>
                    <div className="col-md-1">
                      <label className="form-label fw-semibold">Reps</label>
                      <Input
                        id={`reps-${dayIndex}-${exerciseIndex}`}
                        type="text"
                        inputMode="numeric"
                        value={exercise.reps}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                          const value = e.target.value.replace(/[^0-9]/g, '');
                          updateExercise(dayIndex, exerciseIndex, "reps", value === '' ? '' : Number(value));
                        }}
                        placeholder="Reps"
                        style={{
                          borderRadius: '8px',
                          border: '1px solid #bdbdbd',
                          padding: '6px 10px',
                          width: '100%',
                        }}
                      />
                      {showNestErrors(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].reps`)}
                    </div>
                    <div className="col-md-2">
                      <label className="form-label fw-semibold">Descanso (s)</label>
                      <Input
                        id={`restTime-${dayIndex}-${exerciseIndex}`}
                        type="text"
                        inputMode="numeric"
                        value={exercise.restTime}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                          const value = e.target.value.replace(/[^0-9]/g, '');
                          updateExercise(dayIndex, exerciseIndex, "restTime", value === '' ? '' : Number(value));
                        }}
                        placeholder="Descanso (s)"
                        style={{
                          borderRadius: '8px',
                          border: '1px solid #bdbdbd',
                          padding: '6px 10px',
                          width: '100%',
                        }}
                      />
                      {showNestErrors(`routineExercises[${dayIndex}].exercises[${exerciseIndex}].restTime`)}
                    </div>
                  </div>
                </div>
              ))}

              <div className="">
                <Button type="button" color="info" onClick={() => addExercise(dayIndex)}>
                  Añadir Ejercicio
                </Button>
              </div>
            </div>
          ))}
        </CardBody>

        <CardFooter className="d-flex justify-content-center align-items-center">
          <div className="mx-2">
            <Button 
              type="button" 
              color="info"
              onClick={addDay}
            >
              Añadir Día
            </Button>
          </div>

          <div className="mx-2">
            <Button 
              type="submit" 
              color="primary"
            >
              {isLoading ? <Spinner isSmall /> : `${mode} Rutina`}
            </Button>
          </div>
        </CardFooter>


      </form>
    </Fragment>
  );
};

export default RoutineForm;