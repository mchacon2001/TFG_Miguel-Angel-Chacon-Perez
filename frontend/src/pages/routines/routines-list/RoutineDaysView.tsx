import { useContext, useCallback, Fragment, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import useFetch from "../../../hooks/useFetch";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import { CustomTable } from "../../../components/table/CustomTable";
import Page from "../../../layout/Page/Page";
import SubHeader, {
  SubHeaderLeft,
  SubHeaderRight,
  SubheaderSeparator,
} from "../../../layout/SubHeader/SubHeader";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import ErrorMessage from "../../../components/ErrorMessage";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import { routinesMenu } from "../../../menu";
import { RoutineService } from "../../../services/routines/routineService";
import { Popover, Typography } from "@mui/material";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import { userIsAdmin } from "../../../utils/userIsAdmin";
import { userIsSuperAdmin } from "../../../utils/userIsSuperAdmin";

const RoutinesDaysView = () => {
  const { id = "", tabURL = "" } = useParams<{ id: string; tabURL: string }>();
  const { userCan } = useContext(PrivilegeContext);
  const navigate = useNavigate();
  const routineService = new RoutineService();
  const user = useSelector((state: RootState) => state.auth.user);
  const isAdmin = userIsAdmin() || userIsSuperAdmin();
  
  

  const [anchorEl, setAnchorEl] = useState<HTMLElement | null>(null);
  const [popoverExercises, setPopoverExercises] = useState<any[]>([]);

  const handlePopoverOpen = (
    event: React.MouseEvent<HTMLElement>,
    exercises: any[]
  ) => {
    setAnchorEl(event.currentTarget);
    setPopoverExercises(exercises);
  };

  const handlePopoverClose = () => {
    setAnchorEl(null);
    setPopoverExercises([]);
  };

  const open = Boolean(anchorEl);
  const idPopover = open ? "exercise-popover" : undefined;

  const [data, loading, error, refetch] = useFetch(
    useCallback(async () => {
      const response = await routineService.getRoutineWithDays(id);
      return response.getResponseData();
    }, [id])
  );

  const [dataRoutine, loadingRoutine, errorRoutine, refetchRoutine] = useFetch(
    useCallback(async () => {
      const response = await routineService.getRoutineById(id);
      return response.getResponseData();
    }, [id])
  );


  return (
    <Fragment>
      <SubHeader>
      <SubHeaderLeft>
  <div className="d-flex flex-column gap-2">
    <div className="d-flex align-items-center gap-3 flex-wrap">
      <CardTitle className="mb-0">Días de la Rutina</CardTitle>

      {dataRoutine?.routineCategory?.name && (
        <span
          className="px-3 py-2 rounded-pill"
          style={{
            background: 'linear-gradient(90deg, #FFD580, #FFCC70)',
            fontWeight: 600,
            fontSize: '0.9rem',
            color: '#4a3d00',
            boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
            display: 'inline-block',
          }}
        >
          Categoría: {dataRoutine.routineCategory.name}
        </span>
      )}
    </div>
  </div>

  <SubheaderSeparator />

  {(isAdmin || (user && dataRoutine?.user.id === user.id)) && userCan("edit", "routines") && (
    <div>
      <Button
        color="light"
        icon="Add"
        isLight
        onClick={() => navigate("edit")}
      >
        Añadir Día
      </Button>
    </div>
  )}
    
</SubHeaderLeft>
<SubHeaderRight>
  <Button
    color="light"
    icon="ArrowBack"
    isLight
    onClick={() => navigate(-1)}
  >
    Volver
  </Button>
</SubHeaderRight>
  </SubHeader>

  <Page container="fluid">
  <div className="row g-4 align-items-stretch">
  <div className="col-md-4">
    <Card stretch className="h-100 shadow-sm">
      {!loading && !error ? (
        Array.isArray(data?.days) && data.days.length > 0 ? (
          <>
            <CustomTable
              data={data.days}
              pagination={false}
              className="table table-hover"
              columns={[
                {
                  name: "Día",
                  keyValue: "dayNumber",
                  className: "text-center",
                  render: (element: any) => (
                    <div
                      className="text-center name-link cursor-pointer fw-bold"
                      onClick={() =>
                        navigate(
                          `${routinesMenu.routines.path}/${id}/${element.dayNumber}/view`
                        )
                      }
                    >
                      {element.dayNumber}
                    </div>
                  ),
                },
                {
                  name: "Ejercicios",
                  keyValue: "quantity",
                  className: "text-center",
                  render: (element: any) => {
                    const count = element.routineHasExercise?.length || 0;
                    return (
                      <div className="text-center">
                        <Button
                          size="sm"
                          color="info"
                          onClick={(e: any) =>
                            handlePopoverOpen(e, element.routineHasExercise)
                          }
                        >
                          {count}
                        </Button>
                      </div>
                    );
                  },
                },
                {
                  name: "Acciones",
                  className: "min-w-100px text-end",
                  isActionCell: true,
                },
              ]}
              actions={[
                {
                  title: "Visualizar",
                  buttonType: "icon",
                  iconColor: "text-success",
                  iconPath: "/media/icons/duotune/general/gen060.svg",
                  additionalClasses: "text-success",
                  description: "Ver detalles del día",
                  hide: () => !userCan("get", "routines"),
                  callback: (item: any) => {
                    navigate(`${routinesMenu.routines.path}/${id}/${item.dayNumber}/view`);
                  },
                },
                {
                  title: "Editar",
                  buttonType: "icon",
                  iconColor: "text-info",
                  iconPath: "/media/icons/duotune/general/gen055.svg",
                  additionalClasses: "text-primary",
                  description: "Editar rutina",
                  hide: () => !userCan("edit", "routines"),
                  callback: () => {
                    navigate("edit");
                  },
                },
              ]}
            />
            <Popover
              id={idPopover}
              open={open}
              anchorEl={anchorEl}
              onClose={handlePopoverClose}
              anchorOrigin={{
                vertical: "bottom",
                horizontal: "left",
              }}
            >
              <Typography sx={{ p: 2 }}>
                {popoverExercises.length > 0 ? (
                  <ul className="mb-0 ps-3">
                    {popoverExercises.map((exercise, idx) => (
                      <li key={idx}>
                        {exercise.exercise?.name || "Ejercicio sin nombre"}
                      </li>
                    ))}
                  </ul>
                ) : (
                  ""
                )}
              </Typography>
            </Popover>
          </>
        ) : (
          <p className="text-center my-5">
            No hay días disponibles para esta rutina.
          </p>
        )
      ) : (
        <Loader />
      )}
    </Card>
  </div>

    <div className="col-md-8">
      <Card className="h-100 shadow-sm px-4 py-3">
        {!loadingRoutine && dataRoutine ? (
          <>
            <h5 className="mb-3">{dataRoutine.name}</h5>

            {dataRoutine.routineCategory?.name && (
              <span className="badge bg-warning text-dark mb-3 px-3 py-2 fs-6">
                Categoría: {dataRoutine.routineCategory.name}
              </span>
            )}

            <hr />

            <div className="mb-3">
              <h6 className="text-muted">Descripción</h6>
              <p className="mb-0">
                {dataRoutine.description || "Sin descripción disponible."}
              </p>
            </div>

            {dataRoutine.notes && (
              <div className="mb-3">
                <h6 className="text-muted">Notas</h6>
                <p className="mb-0">{dataRoutine.notes}</p>
              </div>
            )}
          </>
        ) : (
          <Loader />
        )}
      </Card>
    </div>
  </div>
</Page>

    </Fragment>
  );
};

export default RoutinesDaysView;
