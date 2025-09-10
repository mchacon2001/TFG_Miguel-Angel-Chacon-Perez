import { Fragment, useCallback, useContext, useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import useFetch from "../../../hooks/useFetch";
import SubHeader, {
  SubHeaderLeft,
  SubHeaderRight,
  SubheaderSeparator,
} from "../../../layout/SubHeader/SubHeader";
import { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import Button from "../../../components/bootstrap/Button";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { RoutineHasExercise } from "../../../type/exercise-type";
import { RoutineRegisterService } from "../../../services/routine-register/routineRegisterService";
import { toast } from "react-toastify";
import { RoutineRegisterExercisesService } from "../../../services/routine-register-exercises/routineRegisterExercisesService";

const RoutineRegister = () => {
  const { id = "", dayNumber = "" } = useParams<{
    id: string;
    dayNumber: string;
  }>();
  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const routineRegisterService = new RoutineRegisterService();
  const routineRegisterExercisesService = new RoutineRegisterExercisesService();

  const [data, loading] = useFetch(
    useCallback(async () => {
      const response = await routineRegisterService.getRoutineRegisterById(
        id as string
      );
      return response.getResponseData();
    }, [id])
  );

  const [elapsedTime, setElapsedTime] = useState(0);

  useEffect(() => {
    if (!data) return;
    const startTimeStr = data.startTime?.date;
    if (!startTimeStr) return;
    const startTime = new Date(startTimeStr).getTime();
    const updateElapsed = () => {
      const now = Date.now();
      setElapsedTime(Math.floor((now - startTime) / 1000));
    };
    updateElapsed();
    const interval = setInterval(updateElapsed, 1000);
    return () => clearInterval(interval);
  }, [data]);

  const formatTime = (seconds: number) => {
    const h = Math.floor(seconds / 3600)
      .toString()
      .padStart(2, "0");
    const m = Math.floor((seconds % 3600) / 60)
      .toString()
      .padStart(2, "0");
    const s = (seconds % 60).toString().padStart(2, "0");
    return `${h}:${m}:${s}`;
  };

  const [completedSets, setCompletedSets] = useState<{ [key: string]: boolean }>({});
  const [setValues, setSetValues] = useState<{ [key: string]: { reps: number; rest: number } }>({});
  // Estado para errores de validación
  const [validationErrors, setValidationErrors] = useState<{ [key: string]: string }>({});

  useEffect(() => {
    if (!data) return;
    const completed: { [key: string]: boolean } = {};
    const values: { [key: string]: { reps: number; rest: number } } = {};

    const routine = data.routines;
    const exercisesForDay = routine?.routineHasExercise?.filter(
      (exercise: RoutineHasExercise) => exercise.day.toString() === dayNumber
    );

    // Procesar los ejercicios completados desde routineRegisterExercises
    if (Array.isArray(data.routineRegisterExercises) && data.routineRegisterExercises.length > 0) {
      exercisesForDay?.forEach((exerciseData: RoutineHasExercise, exerciseIndex: number) => {
        // Buscar todos los registros de este ejercicio
        const exerciseRegisters = data.routineRegisterExercises.filter((reg: any) => 
          reg.exercise?.id === exerciseData.exercise.id
        );

        exerciseRegisters.forEach((reg: any) => {
          const setIdx = (reg.sets ?? 1) - 1;
          const key = `${exerciseIndex}_${setIdx}`;
          completed[key] = true;
          values[key] = {
            reps: reg.reps ?? exerciseData.reps ?? "",
            rest: reg.rest ?? exerciseData.restTime ?? "",
          };
        });
      });
    }

    setCompletedSets(completed);
    setSetValues(values);
  }, [data, dayNumber]);

  // Función para validar que solo se permitan números enteros
  const handleNumericInput = (e: React.KeyboardEvent<HTMLInputElement>) => {
    // Permitir teclas de control (backspace, delete, tab, escape, enter, etc.)
    const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
    
    // Si es una tecla de control, permitir
    if (allowedKeys.includes(e.key)) {
      return;
    }
    
    // Si no es un número (0-9), prevenir la entrada
    if (!/^[0-9]$/.test(e.key)) {
      e.preventDefault();
    }
  };

  // Función para manejar el pegado y asegurar que solo contenga números
  const handlePaste = (e: React.ClipboardEvent<HTMLInputElement>) => {
    const pastedText = e.clipboardData.getData('text');
    // Si el texto pegado no son solo números, prevenir el pegado
    if (!/^\d+$/.test(pastedText)) {
      e.preventDefault();
    }
  };

  // Función para validar campos
  const validateSet = (exerciseIndex: number, setIndex: number): boolean => {
    const weightInput = document.getElementsByName(
      `exercise_${exerciseIndex}_sets_${setIndex}_weight`
    )[0] as HTMLInputElement;
    const repsInput = document.getElementsByName(
      `exercise_${exerciseIndex}_sets_${setIndex}_reps`
    )[0] as HTMLInputElement;
    const restInput = document.getElementsByName(
      `exercise_${exerciseIndex}_sets_${setIndex}_rest`
    )[0] as HTMLInputElement;

    const key = `${exerciseIndex}_${setIndex}`;
    const errors: { [key: string]: string } = { ...validationErrors };

    // Limpiar errores previos para este set
    delete errors[`${key}_weight`];
    delete errors[`${key}_reps`];
    delete errors[`${key}_rest`];

    let isValid = true;

    // Validar peso
    if (!weightInput?.value || weightInput.value.trim() === "" || Number(weightInput.value) <= 0) {
      errors[`${key}_weight`] = "El peso es obligatorio y debe ser mayor a 0";
      isValid = false;
    }

    // Validar repeticiones
    if (!repsInput?.value || repsInput.value.trim() === "" || Number(repsInput.value) <= 0) {
      errors[`${key}_reps`] = "Las repeticiones son obligatorias y deben ser mayor a 0";
      isValid = false;
    }

    // Validar descanso
    if (!restInput?.value || restInput.value.trim() === "" || Number(restInput.value) < 0) {
      errors[`${key}_rest`] = "El tiempo de descanso es obligatorio y debe ser mayor o igual a 0";
      isValid = false;
    }

    setValidationErrors(errors);
    return isValid;
  };

  const handleFinish = async (
    exerciseIndex: number,
    setIndex: number,
    exerciseId: string,
    weight: number,
    reps: number,
    rest: number
  ) => {
    try {
      await routineRegisterExercisesService.createRoutineRegisterExercises({
        routineRegisterId: id,
        exerciseId,
        weight,
        rest,
        reps,
        sets: setIndex + 1,
      });
      
      // Limpiar errores de validación para este set
      const key = `${exerciseIndex}_${setIndex}`;
      const errors = { ...validationErrors };
      delete errors[`${key}_weight`];
      delete errors[`${key}_reps`];
      delete errors[`${key}_rest`];
      setValidationErrors(errors);

      setCompletedSets((prev) => ({
        ...prev,
        [key]: true,
      }));
    } catch (error) {
      toast.error("Error al registrar el ejercicio.");
    }
  };

  // Función para validar que todos los ejercicios estén completados
  const validateAllExercises = (): boolean => {
    const routine = data.routines;
    const exercisesForDay = routine?.routineHasExercise?.filter(
      (exercise: RoutineHasExercise) => exercise.day.toString() === dayNumber
    );

    if (!exercisesForDay || exercisesForDay.length === 0) {
      toast.error("No hay ejercicios para completar");
      return false;
    }

    let allCompleted = true;
    const incompleteSets: string[] = [];

    exercisesForDay.forEach((exerciseData: RoutineHasExercise, exerciseIndex: number) => {
      for (let setIndex = 0; setIndex < exerciseData.sets; setIndex++) {
        const key = `${exerciseIndex}_${setIndex}`;
        if (!completedSets[key]) {
          allCompleted = false;
          incompleteSets.push(`${exerciseData.exercise.name} - Serie ${setIndex + 1}`);
        }
      }
    });

    if (!allCompleted) {
      toast.error(`Debes completar todas las series antes de finalizar la rutina. Series pendientes: ${incompleteSets.join(", ")}`);
      return false;
    }

    return true;
  };

  const handleFinishRoutine = async () => {
    if (!validateAllExercises()) {
      return;
    }

    try {
      await routineRegisterService.finishRoutineRegister(id as string);
      toast.success("Rutina finalizada correctamente");
      navigate(-1);
    } catch (error) {
      toast.error("Error al finalizar la rutina.");
    }
  };

  if (loading) return <Loader />;
  if (!data) return null;

  const routine = data.routines;
  const exercisesForDay = routine?.routineHasExercise?.filter(
    (exercise: RoutineHasExercise) => exercise.day.toString() === dayNumber
  );

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Button
            color="primary"
            isLink
            icon="ArrowBack"
            onClick={() => navigate(-1)}
          />
          <SubheaderSeparator />
          <CardTitle className="me-4 fs-4">{routine?.name}</CardTitle>
        </SubHeaderLeft>
        <SubHeaderRight>
          <div className="fs-5 fw-bold text-primary">
            {formatTime(elapsedTime)}
          </div>
        </SubHeaderRight>
      </SubHeader>

      <Page container="fluid">
        <div className="row">
          <div className="col-md-12">
            <form onSubmit={(e) => e.preventDefault()}>
              {exercisesForDay?.length > 0 ? (
                exercisesForDay.map(
                  (exerciseData: RoutineHasExercise, index: number) => {
                    const routineHasExercises = data.routineHasExercises || data.routines?.routineHasExercise || [];
                    const routineHasExercise = routineHasExercises.find(
                      (rhe: any) =>
                        (rhe.exercise?.id || rhe.exerciseId) === exerciseData.exercise.id &&
                        (rhe.day?.toString() || rhe.day) === dayNumber
                    );
                    const routineRegisterExercises = routineHasExercise?.routineRegisterExercises || [];
                    
                    return (
                      <div key={index} className="card mb-4">
                        <div className="card-body">
                          <h5 className="card-title text-center mb-4">
                            {exerciseData.exercise.name}
                          </h5>
                          <div className="table-responsive">
                            <table className="table table-bordered routine-mobile-table">
                              <thead className="table-light">
                                <tr>
                                  <th>Set</th>
                                  <th>Peso (kg)</th>
                                  <th>Reps</th>
                                  <th>Descanso (segundos)</th>
                                  <th>Completado</th>
                                </tr>
                              </thead>
                              <tbody>
                                {Array.from(
                                  { length: exerciseData.sets },
                                  (_, setIndex) => {
                                    const key = `${index}_${setIndex}`;
                                    // Buscar el registro específico para este set
                                    const reg = data.routineRegisterExercises?.find((r: any) => 
                                      r.exercise?.id === exerciseData.exercise.id && 
                                      (r.sets ?? 1) === setIndex + 1
                                    );

                                    return (
                                      <tr key={setIndex}>
                                        <td>{setIndex + 1}</td>
                                        <td>
                                          <input
                                            type="number"
                                            className={`form-control ${validationErrors[`${key}_weight`] ? 'is-invalid' : ''}`}
                                            min="0"
                                            step="1"
                                            name={`exercise_${index}_sets_${setIndex}_weight`}
                                            defaultValue={reg?.weight ?? ""}
                                            readOnly={!!completedSets[key]}
                                            placeholder="Peso en kg"
                                            onKeyDown={handleNumericInput}
                                            onPaste={handlePaste}
                                          />
                                          {validationErrors[`${key}_weight`] && (
                                            <div className="invalid-feedback">
                                              {validationErrors[`${key}_weight`]}
                                            </div>
                                          )}
                                        </td>
                                        <td>
                                          <input
                                            type="number"
                                            className={`form-control ${validationErrors[`${key}_reps`] ? 'is-invalid' : ''}`}
                                            min="1"
                                            step="1"
                                            name={`exercise_${index}_sets_${setIndex}_reps`}
                                            defaultValue={
                                              reg?.reps ??
                                              setValues[key]?.reps ??
                                              exerciseData.reps ??
                                              ""
                                            }
                                            readOnly={!!completedSets[key]}
                                            placeholder="Repeticiones"
                                            onKeyDown={handleNumericInput}
                                            onPaste={handlePaste}
                                          />
                                          {validationErrors[`${key}_reps`] && (
                                            <div className="invalid-feedback">
                                              {validationErrors[`${key}_reps`]}
                                            </div>
                                          )}
                                        </td>
                                        <td>
                                          <input
                                            type="number"
                                            className={`form-control ${validationErrors[`${key}_rest`] ? 'is-invalid' : ''}`}
                                            min="0"
                                            step="1"
                                            name={`exercise_${index}_sets_${setIndex}_rest`}
                                            defaultValue={
                                              reg?.rest ??
                                              setValues[key]?.rest ??
                                              exerciseData.restTime ??
                                              ""
                                            }
                                            readOnly={!!completedSets[key]}
                                            placeholder="Descanso en segundos"
                                            onKeyDown={handleNumericInput}
                                            onPaste={handlePaste}
                                          />
                                          {validationErrors[`${key}_rest`] && (
                                            <div className="invalid-feedback">
                                              {validationErrors[`${key}_rest`]}
                                            </div>
                                          )}
                                        </td>
                                        <td>
                                          <div className="form-check form-switch">
                                            <input
                                              className="form-check-input"
                                              type="checkbox"
                                              id={`done-switch-${index}-${setIndex}`}
                                              checked={!!completedSets[key]}
                                              disabled={!!completedSets[key]}
                                              onChange={async (e) => {
                                                if (e.target.checked && !completedSets[key]) {
                                                  // Validar antes de continuar
                                                  if (!validateSet(index, setIndex)) {
                                                    e.target.checked = false;
                                                    toast.error("Por favor, completa todos los campos obligatorios");
                                                    return;
                                                  }

                                                  const weightInput = document.getElementsByName(
                                                    `exercise_${index}_sets_${setIndex}_weight`
                                                  )[0] as HTMLInputElement;
                                                  const repsInput = document.getElementsByName(
                                                    `exercise_${index}_sets_${setIndex}_reps`
                                                  )[0] as HTMLInputElement;
                                                  const restInput = document.getElementsByName(
                                                    `exercise_${index}_sets_${setIndex}_rest`
                                                  )[0] as HTMLInputElement;

                                                  await handleFinish(
                                                    index,
                                                    setIndex,
                                                    exerciseData.exercise.id,
                                                    Number(weightInput?.value || 0),
                                                    Number(repsInput?.value || 0),
                                                    Number(restInput?.value || 0)
                                                  );
                                                }
                                              }}
                                            />
                                            <label className="form-check-label" htmlFor={`done-switch-${index}-${setIndex}`}>
                                              {completedSets[key] ? (
                                                <span className="badge bg-success">✓ Completado</span>
                                              ) : (
                                                <span className="badge bg-warning">Pendiente</span>
                                              )}
                                            </label>
                                          </div>
                                        </td>
                                      </tr>
                                    );
                                  }
                                )}
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    );
                  }
                )
              ) : (
                <p>No hay ejercicios para este día.</p>
              )}
              {exercisesForDay?.length > 0 && (
                <div className="text-center mb-4">
                  <button
                    type="button"
                    className="btn btn-danger btn-lg"
                    onClick={handleFinishRoutine}
                  >
                    Finalizar rutina
                  </button>
                </div>
              )}
            </form>
          </div>
        </div>
      </Page>
    </Fragment>
  );
};

export default RoutineRegister;