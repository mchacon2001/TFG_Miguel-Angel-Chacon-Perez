import { Fragment, useCallback, useContext, useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import useFetch from "../../../hooks/useFetch";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import Button from "../../../components/bootstrap/Button";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import useHandleErrors from "../../../hooks/useHandleErrors";
import { adminMenu } from "../../../menu";
import { DietApiResponse, DietHasFood } from "../../../type/diet-type";
import { DietService } from "../../../services/diets/dietService";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import useFilters from "../../../hooks/useFilters";
import moment from "moment";
import { CardBody } from "../../../components/bootstrap/Card";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";

const initialFilters = {
  diet_id: "",
  date: {
    from: moment().subtract(1, "months").format("YYYY-MM-DD"),
    to: moment().format("YYYY-MM-DD"),
    mode: "days",
  },
  show_by: "day",
};

const WEEK_DAYS = [
  "Lunes",
  "Martes",
  "Miércoles",
  "Jueves",
  "Viernes",
  "Sábado",
  "Domingo",
];

const MEALS = [
  "Desayuno",
  "Media Mañana",
  "Almuerzo",
  "Merienda",
  "Cena",
];

const DietView = () => {
  const { id = "", dayNumber = "" } = useParams<{ id: string; dayNumber: string }>();
  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const { handleErrors } = useHandleErrors();
  const service = new DietService();
  const isAdmin = userIsAdmin() || userIsSuperAdmin();
  const { filters, updateFilters } = useFilters(initialFilters, [], 1, 9999999);
  const [openDay, setOpenDay] = useState<string | null>(null);
  const [openDays, setOpenDays] = useState<number[]>([]);
  const user = useSelector((state: RootState) => state.auth.user);

  
  

  const [data, loading] = useFetch(
    useCallback(async () => {
      const response = await service.getDietWithDays(id as string);
      return response.getResponseData() as DietApiResponse;
    }, [id])
  );

  const _handleDelete = async () => {
    try {
      const response = (await service.deleteDiet(id)).getResponseData();
      if (response.success) {
        navigate(-1);
        setTimeout(() => {
          toast.success("Dieta eliminada correctamente");
        }, 100);
      } else {
        handleErrors(response);
      }
    } catch (error: any) {
      handleErrors(error);
    }
  };

  useEffect(() => {
    if (id) updateFilters({ diet_id: id });
  }, [id]);

  if (loading) return <Loader />;
  if (!data) return null;

  const daysData = data.days || {};

  const getDayNutrition = (dayName: string) => {
    let kcal = 0, protein = 0, carbs = 0, fats = 0;
    MEALS.forEach((meal) => {
      const foods = daysData?.[dayName]?.[meal] || [];
      foods.forEach((foodObj: any) => {
        const food = foodObj.food;
        const qty = foodObj.amount || 0;
        if (food) {
          kcal += ((food.calories ?? 0) / 100) * qty;
          protein += ((food.proteins ?? 0) / 100) * qty;
          carbs += ((food.carbs ?? 0) / 100) * qty;
          fats += ((food.fats ?? 0) / 100) * qty;
        }
      });
    });
    return {
      kcal: Math.round(kcal),
      protein: Math.round(protein),
      carbs: Math.round(carbs),
      fats: Math.round(fats),
    };
  };

  const isKcalOutOfRange = (kcal: number, goal: number) => {
    if (!goal || goal === 0) return false;
    const min = goal * 0.90;
    const max = goal * 1.10;
    return kcal < min || kcal > max;
  };

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
          <SubheaderSeparator />
          <CardTitle className="me-4 fs-4">{data?.name}</CardTitle>
        </SubHeaderLeft>
        <SubHeaderRight>
          {(isAdmin || (user && data?.creator === user.id)) && userCan("edit", "diets") && (
            <Button color="primary" isLink icon="Edit" onClick={() => navigate(`${adminMenu.diets.path}/${id}/edit`)} />
          )}
          {(isAdmin || (user && data?.creator === user.id)) && userCan("delete", "diets") && (
            <Button color="danger" isLink icon="Delete" onClick={_handleDelete} />
          )}
        </SubHeaderRight>
      </SubHeader>

      <Page container="fluid">
        <div className="row">
          <div className="col-lg-8 col-12">
            <div className="accordion" id="dietAccordion">
              {WEEK_DAYS.map((dayName, dayIdx) => {
                const nutrition = getDayNutrition(dayName);
                const kcalColor = isKcalOutOfRange(nutrition.kcal, Number(data?.goal))
                  ? "red"
                  : openDays.includes(dayIdx)
                    ? "#fff"
                    : "#000";
                return (
                  <div className="accordion-item mb-2" key={dayIdx}>
                    <h2 className="accordion-header" id={`heading${dayIdx}`}>
                      <button
                        className={`accordion-button mt-4 ${openDays.includes(dayIdx) ? '' : 'collapsed'}`}
                        type="button"
                        onClick={() =>
                          setOpenDays((prev) =>
                            prev.includes(dayIdx)
                              ? prev.filter((idx) => idx !== dayIdx)
                              : [...prev, dayIdx]
                          )
                        }
                        aria-expanded={openDays.includes(dayIdx)}
                        aria-controls={`collapse${dayIdx}`}
                        style={{
                          fontWeight: 600,
                          borderRadius: openDays.includes(dayIdx) ? "1.5rem" : "1.5rem",
                          backgroundColor: openDays.includes(dayIdx) ? "#ffbb00" : "#fff",
                          color: openDays.includes(dayIdx) ? "#fff" : "#000",
                          boxShadow: !openDays.includes(dayIdx) ? "0 2px 8px rgba(0,0,0,0.04)" : "",
                          border: "1px solid #eee",
                          transition: "all 0.3s ease"
                        }}
                      >
                        {dayName}
                      </button>
                    </h2>
                    <div
                      id={`collapse${dayIdx}`}
                      className={`accordion-collapse collapse${openDays.includes(dayIdx) ? ' show' : ''}`}
                      aria-labelledby={`heading${dayIdx}`}
                    >
                      <div className="accordion-body p-0">
                        <div className="mb-4 border-0 rounded-0 p-3">
                          {MEALS.map((mealName, mealIdx) => {
                            const foods = daysData[dayName]?.[mealName] || [];
                            return (
                              <Card key={mealIdx} className="mb-3 bg-light-subtle">
                                <CardBody>
                                  <h5 className="mb-3 text-secondary">
                                    {mealName}
                                    {(!foods || foods.length === 0) && (
                                      <span className="ms-2 badge bg-warning text-dark">Ayuno</span>
                                    )}
                                  </h5>
                                  {Array.isArray(foods) && foods.length > 0 ? (
                                    <ul className="list-group list-group-flush">
                                      {foods.map((foodObj: any, foodIdx: number) => (
                                        <li key={foodIdx} className="list-group-item d-flex justify-content-between">
                                          <div>
                                            {foodObj.food?.name}
                                            {foodObj.food?.description && foodObj.food?.description.trim() !== "" && (
                                              <span className="text-muted ms-2">({foodObj.food.description})</span>
                                            )}
                                          </div>
                                          <div>
                                            {foodObj.amount} gramos
                                          </div>
                                        </li>
                                      ))}
                                    </ul>
                                  ) : null}
                                </CardBody>
                              </Card>
                            );
                          })}
                        </div>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
            {openDays.length === 0 && (
              <div className="text-center text-muted my-5">
                <i className="bi bi-emoji-neutral display-1 mb-3 d-block" />
                <h4 className="fw-bold">No hay ningún día abierto</h4>
                <p className="text-secondary">Haz clic en un día para ver sus comidas y alimentos.</p>
              </div>
            )}
          </div>
          <div className="col-lg-4 col-12">
            <div
              className="sticky-top"
              style={{
                top: "90px",
                zIndex: 1,   
              }}
            >
              <div className="row mb-4">
                <div className="col-12 mb-3">
                  <Card className="text-center" style={{ backgroundColor: "#ff9800", color: "#fff" }}>
                    <CardBody>
                      <h5 className="mb-0">🎯 Objetivo diario</h5>
                      <h3>
                        {data?.goal ? `${data.goal} kcal` : "-"}
                      </h3>
                    </CardBody>
                  </Card>
                </div>
                <div className="col-12 mb-3">
                  <Card className="text-center bg-success text-white">
                    <CardBody>
                      <h5 className="mb-0">🥗 Total de alimentos</h5>
                      <h3>
                        {
                          WEEK_DAYS.reduce((acc, day) =>
                            acc + MEALS.reduce((sum, meal) => {
                              const foods = daysData?.[day]?.[meal] || [];
                              return sum + foods.length;
                            }, 0)
                          , 0)
                        }
                      </h3>
                    </CardBody>
                  </Card>
                </div>
                <div className="col-12">
                  <Card className="text-center bg-warning text-dark">
                    <CardBody>
                      <h5 className="mb-0">⏳ Número de ayunos</h5>
                      <h3>
                        {
                          WEEK_DAYS.reduce((acc, day) =>
                            acc + MEALS.reduce((sum, meal) => {
                              const foods = daysData?.[day]?.[meal] || [];
                              return sum + (foods.length === 0 ? 1 : 0);
                            }, 0)
                          , 0)
                        }
                      </h3>
                    </CardBody>
                  </Card>
                </div>
              </div>
              <div>
                <h6 className="fw-bold mb-2">Resumen nutricional por día</h6>
                <ul className="list-group">
                  {WEEK_DAYS.map((dayName, dayIdx) => {
                    const nutrition = getDayNutrition(dayName);
                    const kcalColor = isKcalOutOfRange(nutrition.kcal, Number(data?.goal))
                      ? "red"
                      : "#212529";
                    return (
                      <li key={dayIdx} className="list-group-item d-flex flex-column align-items-start">
                        <span className="fw-bold">{dayName}</span>
                        <span style={{ color: kcalColor }}>
                          {nutrition.kcal} kcal
                        </span>
                        <span>
                          Proteinas: {nutrition.protein}g | Carbohidratos: {nutrition.carbs}g | Grasas: {nutrition.fats}g
                        </span>
                      </li>
                    );
                  })}
                </ul>
              </div>
            </div>
          </div>
        </div>
      </Page>
    </Fragment>
  );
};

export default DietView;
