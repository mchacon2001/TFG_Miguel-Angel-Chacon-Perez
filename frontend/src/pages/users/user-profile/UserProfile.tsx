import { FC, Fragment, useCallback, useContext, useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator, } from "../../../layout/SubHeader/SubHeader";
import PersonalInfoCard from "./PersonalInfoCard";
import useFetch from "../../../hooks/useFetch";
import { User } from "../../../type/user-type";
import { UserService } from "../../../services/users/userService";
import { getUserRolesByObject } from "../../../helpers/helpers";
import moment from "moment";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { adminMenu } from "../../../menu";
import { toast } from "react-toastify";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import Modal, { ModalBody, ModalHeader } from "../../../components/bootstrap/Modal";
import { Category } from "../../../components/icon/material-icons";
import { CardTitle } from "../../../components/bootstrap/Card";
import { useFormik } from "formik";
import * as yup from "yup";
import { Line } from "react-chartjs-2";
import { Chart, CategoryScale, LinearScale, PointElement, LineElement, Legend, Tooltip } from "chart.js";
import type { ChartOptions } from "chart.js";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import ReportsModal from "./ReportsModal";
Chart.register(CategoryScale, LinearScale, PointElement, LineElement, Legend, Tooltip);

const UserProfile: FC = () => {
  const { id = "" } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const { handleErrors } = useHandleErrors();
  const userService = new UserService();
  const user = useSelector((state: RootState) => state.auth.user);

  const [refreshKey, setRefreshKey] = useState(0);

  const [data, loading] = useFetch(useCallback(async () => {
    const response = await userService.getUserById(id as string);
    return response.getResponseData() as User;
  }, [id, refreshKey]));

  const [showPhysicalModal, setShowPhysicalModal] = useState(false);
  const [showMentalModal, setShowMentalModal] = useState(false);
  const [showReportsModal, setShowReportsModal] = useState(false);

  // Solo obtener datos estadísticos si data existe y NO es admin/superadmin viendo su propio perfil
  const [physicalStatsRaw] = useFetch(useCallback(async () => {
    if (!data) return []; 
    
    // Verificar si el usuario es superadministrador o administrador
    const isSuperAdmin = data.userRoles?.some((userRole: any) => 
      userRole.role?.name?.toLowerCase() === 'superadministrador' || 
      userRole.role?.id === 1
    );
    
    const isAdmin = data.userRoles?.some((userRole: any) => 
      userRole.role?.name?.toLowerCase() === 'administrador' || 
      userRole.role?.id === 2
    );

    const isAdminOrSuperAdmin = isSuperAdmin || isAdmin;
    const shouldHideStats = isAdminOrSuperAdmin && user?.id === id;
    
    if (shouldHideStats) {
      return [];
    }
    
    const response = await userService.getPhysicalStats(id as string);
    return response.getResponseData()
  }, [id, refreshKey, data, user?.id]));

  const [mentalStatsRaw] = useFetch(useCallback(async () => {
    if (!data) return []; // Si no hay data, retornar array vacío
    
    // Verificar si el usuario es superadministrador o administrador
    const isSuperAdmin = data.userRoles?.some((userRole: any) => 
      userRole.role?.name?.toLowerCase() === 'superadministrador' || 
      userRole.role?.id === 1
    );
    
    const isAdmin = data.userRoles?.some((userRole: any) => 
      userRole.role?.name?.toLowerCase() === 'administrador' || 
      userRole.role?.id === 2
    );

    const isAdminOrSuperAdmin = isSuperAdmin || isAdmin;
    const shouldHideStats = isAdminOrSuperAdmin && user?.id === id;
    
    if (shouldHideStats) {
      return []; // Retornar array vacío para admins viendo su propio perfil
    }
    
    const response = await userService.getMentalStats(id as string);
    return response?.getResponseData();
  }, [id, refreshKey, data, user?.id]));

  // Nuevo: obtener datos de calorías consumidas
  const [calorieIntakeRaw] = useFetch(useCallback(async () => {
    if (!data) return [];
    
    const isSuperAdmin = data.userRoles?.some((userRole: any) => 
      userRole.role?.name?.toLowerCase() === 'superadministrador' || 
      userRole.role?.id === 1
    );
    
    const isAdmin = data.userRoles?.some((userRole: any) => 
      userRole.role?.name?.toLowerCase() === 'administrador' || 
      userRole.role?.id === 2
    );

    const isAdminOrSuperAdmin = isSuperAdmin || isAdmin;
    const shouldHideStats = isAdminOrSuperAdmin && user?.id === id;
    
    if (shouldHideStats) {
      return [];
    }
    
    try {
      const response = await userService.getCalorieIntake(id as string);
      return response?.getResponseData() || [];
    } catch (error) {
      console.error('Error fetching calorie intake:', error);
      return [];
    }
  }, [id, refreshKey, data, user?.id]));

  const physicalStats = Array.isArray(physicalStatsRaw) ? physicalStatsRaw : [];
  const mentalStats = Array.isArray(mentalStatsRaw) ? mentalStatsRaw : [];
  const calorieIntake = Array.isArray(calorieIntakeRaw) ? calorieIntakeRaw : [];

  // Gráfica 1: Ánimo y calidad del sueño
  const mentalCombinedData = {
    labels: mentalStats.map((s: any) =>
      s && s.recordedAt && s.recordedAt.date
        ? moment(s.recordedAt.date).format("DD/MM/YYYY")
        : ""
    ),
    datasets: [
      {
        label: "Calidad del sueño",
        data: mentalStats.map((s: any) => s?.sleepQuality ?? null),
        borderColor: "#36a2eb",
        backgroundColor: "rgba(54,162,235,0.2)",
        tension: 0.3,
      },
      {
        label: "Ánimo",
        data: mentalStats.map((s: any) => s?.mood ?? null),
        borderColor: "#ff6384",
        backgroundColor: "rgba(255,99,132,0.2)",
        tension: 0.3,
      },
    ],
  };

  // Gráfica 2: Peso
  const weightData = {
    labels: physicalStats.map((s: any) =>
      s && s.recordedAt && s.recordedAt.date
        ? moment(s.recordedAt.date).format("DD/MM/YYYY")
        : ""
    ),
    datasets: [
      {
        label: "Peso (kg)",
        data: physicalStats.map((s: any) => s?.weight ?? null),
        borderColor: "#dbaa07",
        backgroundColor: "rgba(54,162,235,0.2)",
        tension: 0.3,
      },
    ],
  };

  // Gráfica 3: Body Fat
  const bodyFatData = {
    labels: physicalStats.map((s: any) =>
      s && s.recordedAt && s.recordedAt.date
        ? moment(s.recordedAt.date).format("DD/MM/YYYY")
        : ""
    ),
    datasets: [
      {
        label: "IMC (%)",
        data: physicalStats.map((s: any) => {
          const val = Number(s?.bodyFat);
          return isNaN(val) || val < 0 || val > 100 ? null : val;
        }),
        borderColor: "#4bc0c0",
        backgroundColor: "rgba(75,192,192,0.2)",
        tension: 0.3,
      },
    ],
  };

  // Procesar datos de calorías para calcular las calorías reales del día
  const processCalorieData = (rawData: any[]) => {
    if (!Array.isArray(rawData)) return [];
    
    // Agrupar por fecha y calcular calorías reales: (amount * calories) / 100
    const groupedByDate = rawData.reduce((acc: any, item: any) => {
      if (!item?.date?.date || !item?.amount || !item?.calories) return acc;
      
      const dateStr = moment(item.date.date).format('YYYY-MM-DD');
      
      if (!acc[dateStr]) {
        acc[dateStr] = {
          date: dateStr,
          totalCalories: 0
        };
      }
      
      const realCalories = (Number(item.amount) * Number(item.calories)) / 100;
      acc[dateStr].totalCalories += realCalories;
      
      return acc;
    }, {});
    
    return Object.values(groupedByDate).sort((a: any, b: any) => 
      moment(a.date).valueOf() - moment(b.date).valueOf()
    );
  };

  const processedCalorieData = processCalorieData(calorieIntake);

  // Gráfica 4: Calorías consumidas (actualizada)
  const calorieData = {
    labels: processedCalorieData.map((c: any) =>
      moment(c.date).format("DD/MM/YYYY")
    ),
    datasets: [
      {
        label: "Calorías consumidas",
        data: processedCalorieData.map((c: any) => c.totalCalories),
        borderColor: "#ff9f40",
        backgroundColor: "rgba(255,159,64,0.2)",
        tension: 0.3,
      },
    ],
  };

  const validBodyFatValues = physicalStats
    .map((s: any) => Number(s?.bodyFat))
    .filter((v: number) => !isNaN(v) && v >= 0 && v <= 100);

  const maxBodyFat = validBodyFatValues.length > 0
    ? Math.max(...validBodyFatValues, 0)
    : 100;

  const maxCalories = Math.max(
    ...processedCalorieData.map((c: any) => c.totalCalories || 0),
    0
  );

  const physicalStatsFormik = useFormik({
    initialValues: {
      height: "",
      weight: "",
    },
    validationSchema: yup.object({
      height: yup
        .number()
        .typeError("Debe ser un número")
        .min(80, "Debe ser mayor o igual a 80cm")
        .max(250, "Debe ser menor de 250cm")
        .required("Campo obligatorio"),
      weight: yup
        .number()
        .typeError("Debe ser un número")
        .min(20, "Debe ser mayor o igual a 20kg")
        .max(500, "Debe ser menor de 500kg")
        .required("Campo obligatorio"),
    }),
    onSubmit: async (values, { resetForm }) => {
      try {
        await userService.addPhysicalStats(id, {
          height: Number(values.height),
          weight: Number(values.weight),
        });
        toast.success("Estado físico añadido correctamente");
        setShowPhysicalModal(false);
        resetForm();
        setRefreshKey((k) => k + 1);
      } catch (error: any) {
        handleErrors(error);
      }
    },
  });

  const mentalStatsFormik = useFormik({
    initialValues: {
      mood: "",
      sleepQuality: "",
    },
    validationSchema: yup.object({
      mood: yup
        .number()
        .typeError("Debe ser un número")
        .min(1, "Debe ser mayor o igual a 1")
        .max(10, "Debe ser menor o igual a 10")
        .required("Campo obligatorio"),
      sleepQuality: yup
        .number()
        .typeError("Debe ser un número")
        .min(1, "Debe ser mayor o igual a 1")
        .max(10, "Debe ser menor o igual a 10")
        .required("Campo obligatorio"),
    }),
    onSubmit: async (values, { resetForm }) => {
      try {
        await userService.addMentalStats(id, {
          mood: Number(values.mood),
          sleepQuality: Number(values.sleepQuality),
        });
        toast.success("Estado mental añadido correctamente");
        setShowMentalModal(false);
        resetForm();
        setRefreshKey((k) => k + 1);
      } catch (error: any) {
        handleErrors(error);
      }
    },
  });

  const _handleDelete = async () => {
    try {
      const response = await (await userService.deleteUser(id)).getResponseData();
      if (response.success) {
        navigate(-1);
        setTimeout(() => {
          toast.success("Usuario eliminado correctamente");
        }, 100);
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      handleErrors(error);
    }
  };

  const maxMental = Math.max(
    10,
    ...mentalStats.map((s: any) => Math.max(
      s?.sleepQuality ?? 0,
      s?.mood ?? 0
    ))
  );
  const maxWeight = Math.max(
    ...physicalStats.map((s: any) => s?.weight ?? 0),
    0
  );

  const mentalChartOptions: ChartOptions<"line"> = {
    responsive: true,
    interaction: { mode: "index", intersect: false },
    plugins: { legend: { position: "top" } },
    scales: {
      y: {
        type: "linear",
        display: true,
        position: "left",
        min: 0,
        max: maxMental,
        title: { display: true, text: "Valor" }
      },
      x: {
        ticks: {
          display: false
        },
        grid: {
          display: false
        }
      }
    },
  };

  const minWeight = physicalStats.length > 0
    ? Math.min(...physicalStats.map((s: any) => s?.weight ?? Infinity))
    : 0;

  const weightChartOptions: ChartOptions<"line"> = {
    responsive: true,
    interaction: { mode: "index", intersect: false },
    plugins: { legend: { position: "top" } },
    scales: {
      y: {
        type: "linear",
        display: true,
        position: "left",
        suggestedMin: minWeight !== Infinity ? Math.floor(minWeight * 0.95) : undefined,
        max: maxWeight > 0 ? Math.ceil(maxWeight * 1.1) : undefined,
        title: { display: true, text: "Peso (kg)" }
      },
      x: {
        ticks: {
          display: false
        },
        grid: {
          display: false
        }
      }
    },
  };

  const bodyFatChartOptions: ChartOptions<"line"> = {
    responsive: true,
    interaction: { mode: "index", intersect: false },
    plugins: { legend: { position: "top" } },
    scales: {
      y: {
        type: "linear",
        display: true,
        position: "left",
        min: 0,
        max: maxBodyFat > 0 ? Math.ceil(maxBodyFat * 1.1) : 100,
        title: { display: true, text: "IMC (%)" }
      },
      x: {
        ticks: {
          display: false
        },
        grid: {
          display: false
        }
      }
    },
  };

  const calorieChartOptions: ChartOptions<"line"> = {
    responsive: true,
    interaction: { mode: "index", intersect: false },
    plugins: { legend: { position: "top" } },
    scales: {
      y: {
        type: "linear",
        display: true,
        position: "left",
        min: 0,
        max: maxCalories > 0 ? Math.ceil(maxCalories * 1.1) : 3000,
        title: { display: true, text: "Calorías (kcal)" }
      },
      x: {
        ticks: {
          display: false
        },
        grid: {
          display: false
        }
      }
    },
  };

  const today = moment().format("YYYY-MM-DD");
  const hasPhysicalToday = physicalStats.some(
    (s: any) =>
      s &&
      s.recordedAt &&
      moment(s.recordedAt.date).format("YYYY-MM-DD") === today
  );
  const hasMentalToday = mentalStats.some(
    (s: any) =>
      s &&
      s.recordedAt &&
      moment(s.recordedAt.date).format("YYYY-MM-DD") === today
  );

  const [dailyCaloriesConsumed, setDailyCaloriesConsumed] = useState(0);
  const [dailyCaloriesBurned, setDailyCaloriesBurned] = useState(0);
  const [bmrValue, setBmrValue] = useState(0);


  if (loading) return <Loader />;
  if (!data) return null;

  const isSuperAdmin = data.userRoles?.some((userRole: any) => 
    userRole.role?.name?.toLowerCase() === 'superadministrador' || 
    userRole.role?.id === 1
  );
  
  const isAdmin = data.userRoles?.some((userRole: any) => 
    userRole.role?.name?.toLowerCase() === 'administrador' || 
    userRole.role?.id === 2
  );

  const isAdminOrSuperAdmin = isSuperAdmin || isAdmin;
  const shouldHideStats = isAdminOrSuperAdmin;
  const shouldHideAdvancedInfo = isAdminOrSuperAdmin;

  // Calculate today's consumed calories
  const calculateTodayCalories = () => {
    const today = moment().format('YYYY-MM-DD');
    
    if (!Array.isArray(calorieIntake)) return 0;
    
    const todayIntakes = calorieIntake.filter((item: any) => {
      if (!item?.date?.date) return false;
      return moment(item.date.date).format('YYYY-MM-DD') === today;
    });
    
    return todayIntakes.reduce((total: number, item: any) => {
      if (!item?.amount || !item?.calories) return total;
      const realCalories = (Number(item.amount) * Number(item.calories)) / 100;
      return total + realCalories;
    }, 0);
  };

  const todayCaloriesConsumed = calculateTodayCalories();

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
        </SubHeaderLeft>
        <SubHeaderRight>
          {!shouldHideStats && (
            <Button
              color="warning"
              className="me-2"
              icon="Assessment"
              onClick={() => setShowReportsModal(true)}
            >
              Informes
            </Button>
          )}
          
          {!shouldHideStats && (
            <>
              <Button
                color="success"
                className="me-2"
                onClick={() => setShowPhysicalModal(true)}
                isDisable={hasPhysicalToday}
                title={hasPhysicalToday ? "Ya has registrado el estado físico hoy" : ""}
              >
                Estado físico
              </Button>
              <Button
                color="info"
                className="me-2"
                onClick={() => setShowMentalModal(true)}
                isDisable={hasMentalToday}
                title={hasMentalToday ? "Ya has registrado el estado mental hoy" : ""}
              >
                Estado mental
              </Button>
            </>
          )}
          
          {userCan('edit', 'user') && !isSuperAdmin && (
            <Button color='primary' isLink icon='Edit' onClick={() => navigate(`${adminMenu.users.path}/${id}/edit`)} />
          )}
          {userCan('edit', 'user') && userCan('delete', 'user') && user?.id !== id && !isSuperAdmin && (
            <SubheaderSeparator />
          )}
          {userCan('delete', 'user') && user?.id !== id && !isSuperAdmin && (
            <Button
              color='primary' isLink icon='Delete'
              onClick={() => {
                handleConfirmationAlert({
                  title: "Eliminar usuario",
                  text: "Esta acción es irreversible. ¿Estás seguro de que quieres eliminar este usuario?",
                  icon: "warning",
                  onConfirm: _handleDelete
                })
              }}
            />
          )}
        </SubHeaderRight>
      </SubHeader>

      <Modal isOpen={showPhysicalModal} setIsOpen={setShowPhysicalModal} size='md' titleId='Añadir Estado Físico'>
        <ModalHeader className='ms-2 p-4 gap-4'>
          <Category fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
          <CardTitle className="fs-3">Añadir estado físico</CardTitle>
          <Button className='btn-close fs-5 p-4' onClick={() => setShowPhysicalModal(false)} />
        </ModalHeader>
        <hr className="mt-0" />
        <ModalBody className='px-4'>
          {hasPhysicalToday ? (
            <div className="alert alert-info">
              Ya has registrado el estado físico para hoy.
            </div>
          ) : (
            <form onSubmit={physicalStatsFormik.handleSubmit}>
              <div className="mb-3">
                <label htmlFor="height" className="form-label">Altura (cm)</label>
                <input
                  id="height"
                  name="height"
                  type="number"
                  className={`form-control ${physicalStatsFormik.touched.height && physicalStatsFormik.errors.height ? "is-invalid" : ""}`}
                  value={physicalStatsFormik.values.height}
                  onChange={physicalStatsFormik.handleChange}
                  onBlur={physicalStatsFormik.handleBlur}
                />
                {physicalStatsFormik.touched.height && physicalStatsFormik.errors.height && (
                  <div className="invalid-feedback">{physicalStatsFormik.errors.height}</div>
                )}
              </div>
              <div className="mb-3">
                <label htmlFor="weight" className="form-label">Peso (kg)</label>
                <input
                  id="weight"
                  name="weight"
                  type="number"
                  className={`form-control ${physicalStatsFormik.touched.weight && physicalStatsFormik.errors.weight ? "is-invalid" : ""}`}
                  value={physicalStatsFormik.values.weight}
                  onChange={physicalStatsFormik.handleChange}
                  onBlur={physicalStatsFormik.handleBlur}
                />
                {physicalStatsFormik.touched.weight && physicalStatsFormik.errors.weight && (
                  <div className="invalid-feedback">{physicalStatsFormik.errors.weight}</div>
                )}
              </div>
              <div className="d-flex justify-content-end">
                <Button color="success" type="submit" className="me-2">
                  Guardar
                </Button>
                <Button color="secondary" type="button" onClick={() => setShowPhysicalModal(false)}>
                  Cancelar
                </Button>
              </div>
            </form>
          )}
        </ModalBody>
      </Modal>

      <Modal isOpen={showMentalModal} setIsOpen={setShowMentalModal} size='md' titleId='Añadir Estado Mental'>
        <ModalHeader className='ms-2 p-4 gap-4'>
          <Category fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
          <CardTitle className="fs-3">Añadir estado mental</CardTitle>
          <Button className='btn-close fs-5 p-4' onClick={() => setShowMentalModal(false)} />
        </ModalHeader>
        <hr className="mt-0" />
        <ModalBody className='px-4'>
          {hasMentalToday ? (
            <div className="alert alert-info">
              Ya has registrado el estado mental para hoy.
            </div>
          ) : (
            <form onSubmit={mentalStatsFormik.handleSubmit}>
              <div className="mb-3">
                <label htmlFor="mood" className="form-label">¿Como te sientes hoy del 1 al 10?</label>
                <input
                  id="mood"
                  name="mood"
                  type="number"
                  className={`form-control ${mentalStatsFormik.touched.mood && mentalStatsFormik.errors.mood ? "is-invalid" : ""}`}
                  value={mentalStatsFormik.values.mood}
                  onChange={mentalStatsFormik.handleChange}
                  onBlur={mentalStatsFormik.handleBlur}
                  min={1}
                  max={10}
                />
                {mentalStatsFormik.touched.mood && mentalStatsFormik.errors.mood && (
                  <div className="invalid-feedback">{mentalStatsFormik.errors.mood}</div>
                )}
              </div>
              <div className="mb-3">
                <label htmlFor="sleepQuality" className="form-label">¿Que tal dormiste hoy del 1 al 10</label>
                <input
                  id="sleepQuality"
                  name="sleepQuality"
                  type="number"
                  className={`form-control ${mentalStatsFormik.touched.sleepQuality && mentalStatsFormik.errors.sleepQuality ? "is-invalid" : ""}`}
                  value={mentalStatsFormik.values.sleepQuality}
                  onChange={mentalStatsFormik.handleChange}
                  onBlur={mentalStatsFormik.handleBlur}
                  min={1}
                  max={10}
                />
                {mentalStatsFormik.touched.sleepQuality && mentalStatsFormik.errors.sleepQuality && (
                  <div className="invalid-feedback">{mentalStatsFormik.errors.sleepQuality}</div>
                )}
              </div>
              <div className="d-flex justify-content-end">
                <Button color="success" type="submit" className="me-2">
                  Guardar
                </Button>
                <Button color="secondary" type="button" onClick={() => setShowMentalModal(false)}>
                  Cancelar
                </Button>
              </div>
            </form>
          )}
        </ModalBody>
      </Modal>

      {/* Reports Modal */}
      <ReportsModal isOpen={showReportsModal} setIsOpen={setShowReportsModal} userId={id} />

      <Page container="xxl">
        {data && (
          <>
            <div className="pt-3 pb-5 d-flex align-items-center">
              <span className="display-6 fw-bold me-3">{data.name}</span>
              {getUserRolesByObject(data)?.map((role: string) => (
                <span key={"profile-role" + role} className="border border-dark border-2 text-dark fw-bold px-3 py-2 rounded">
                  {role}
                </span>
              ))}
              <span className="text-muted fs-5 ms-4">Último acceso: {moment(data.lastLogin?.date).format('DD/MM/YYYY')}</span>
            </div>
            <div className="mb-4">
              <PersonalInfoCard
                name={data.name}
                targetWeight={data.targetWeight ?? null}
                sex={data.sex ?? null}
                weight={physicalStats.length > 0 ? physicalStats[physicalStats.length - 1].weight : null}
                height={physicalStats.length > 0 ? physicalStats[physicalStats.length - 1].height : null}
                bodyFat={physicalStats.length > 0 ? physicalStats[physicalStats.length - 1].bodyFat : null}
                bmi={physicalStats.length > 0 ? physicalStats[physicalStats.length - 1].bmi : null}
                physicalUpdated={hasPhysicalToday}
                mentalUpdated={hasMentalToday}
                toGainMuscle={data.toGainMuscle}
                toLoseWeight={data.toLoseWeight}
                toMaintainWeight={data.toMaintainWeight}
                toImprovePhysicalHealth={data.toImprovePhysicalHealth}
                toImproveMentalHealth={data.toImproveMentalHealth}
                fixShoulder={data.fixShoulder}
                fixKnees={data.fixKnees}
                fixBack={data.fixBack}
                rehab={data.rehab}
                // Add calorie tracking
                dailyCaloriesConsumed={todayCaloriesConsumed}
                // Hide advanced info for admin/superadmin profiles
                shouldHideAdvancedInfo={shouldHideAdvancedInfo}
              />
            </div>
            {/* Solo mostrar gráficas si NO debe ocultar estadísticas */}
            {!shouldHideStats && (
              <>
                {/* Primera fila: Peso e IMC */}
                <div className="row mb-4">
                  <div className="col-lg-6 col-md-6 mb-4">
                    {weightData.labels.length > 0 &&
                    weightData.datasets[0].data.some((v: any) => v !== null && v !== undefined) ? (
                      <Line data={weightData} options={weightChartOptions} />
                    ) : (
                      <div className="text-muted">Sin datos suficientes</div>
                    )}
                  </div>
                  <div className="col-lg-6 col-md-6 mb-4">
                    {bodyFatData.labels.length > 0 &&
                    bodyFatData.datasets[0].data.some((v: any) => v !== null && v !== undefined) ? (
                      <Line data={bodyFatData} options={bodyFatChartOptions} />
                    ) : (
                      <div className="text-muted">Sin datos suficientes</div>
                    )}
                  </div>
                </div>

                {/* Segunda fila: Calidad del sueño/Ánimo y Calorías */}
                <div className="row">
                  <div className="col-lg-6 col-md-6 mb-4">
                    {mentalCombinedData.labels.length > 0 &&
                    (mentalCombinedData.datasets[0].data.some((v: any) => v !== null && v !== undefined) ||
                      mentalCombinedData.datasets[1].data.some((v: any) => v !== null && v !== undefined)) ? (
                      <Line data={mentalCombinedData} options={mentalChartOptions} />
                    ) : (
                      <div className="text-muted">Sin datos suficientes</div>
                    )}
                  </div>
                  <div className="col-lg-6 col-md-6 mb-4">
                    {processedCalorieData.length > 0 &&
                    calorieData.datasets[0].data.some((v: any) => v !== null && v !== undefined && v > 0) ? (
                      <Line data={calorieData} options={calorieChartOptions} />
                    ) : (
                      <div className="text-muted">Sin datos suficientes</div>
                    )}
                  </div>
                </div>
              </>
            )}
          </>
        )}
      </Page>
    </Fragment>
    
  );
};

export default UserProfile;