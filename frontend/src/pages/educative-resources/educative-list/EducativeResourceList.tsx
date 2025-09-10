import { Fragment, useCallback, useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";
import Card, { CardTitle } from "../../../components/bootstrap/Card";
import Page from "../../../layout/Page/Page";
import SubHeader, { SubHeaderLeft, SubHeaderRight, SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import moment from "moment";
import { PrivilegeContext } from "../../../components/priviledge/PriviledgeProvider";
import { useSelector } from "react-redux";
import { RootState } from "../../../redux/store";
import { Loader } from "../../../components/bootstrap/SpinnerLogo";
import ErrorMessage from "../../../components/ErrorMessage";
import useFetch from "../../../hooks/useFetch";
import { EducativeResourceService } from "../../../services/educativeResources/educativeResourcesService";
import Accordion, { AccordionItem } from "../../../components/bootstrap/Accordion";
import { useFiltersPR } from "../../../components/providers/FiltersProvider";
import EducativeResourceFilters from "./educative-options/EducativeResourceFilters";


export const TYPES_OF_EDUCATIVE_RESOURCES = [
    {label: 'Entrenamientos', value: 'training'},
    {label: 'Salud', value: 'health'},
    {label: 'Nutrición', value: 'nutrition'},
    {label: 'Artículos científicos', value: 'scientific_articles'},
    {label: 'Otro', value: 'other'},
];


const getYoutubeId = (url: string) => {
  const match = url.match(
    /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/
  );
  return match ? match[1] : null;
};



const EducativeResourceList = () => {
  const navigate = useNavigate();
  const { userCan } = useContext(PrivilegeContext);
  const user = useSelector((state: RootState) => state.auth.user);
  const { filters, updateFilters, updateFilterOrder, updatePage, updatePageSize, resetFilters } = useFiltersPR();

  const [playingId, setPlayingId] = useState<string | null>(null);
  const [openTags, setOpenTags] = useState<string[]>([]);

  const [data, loading, error] = useFetch(useCallback(async () => {
    const service = new EducativeResourceService();
    const response = await service.getEducativeResource(filters);
    return response.getResponseData();
  }, [filters]));

  const [resources, setResources] = useState<any[]>([]);

  useEffect(() => {
    if (data && data.educativeResources) {
      setResources(data.educativeResources);
    }
  }, [data]);

  const deleteResource = async (id: string) => {
    const response = (await new EducativeResourceService().deleteEducativeResource(id)).getResponseData();
    if (response.success) {
      toast.success("Recurso educativo eliminado correctamente");
      setResources(prev => prev.filter((r: any) => r.id !== id));
    }
  };

  return (
    <Fragment>
      <SubHeader>
        <SubHeaderLeft>
          <CardTitle>Recursos Educativos</CardTitle>
          {userCan("create", "educative_resources") && (
            <>
              <SubheaderSeparator />
              <Button color="light" icon="Add" isLight onClick={() => navigate("create")}>
                Añadir Recurso
              </Button>
            </>
          )}
        </SubHeaderLeft>
         <SubHeaderRight>
            <EducativeResourceFilters  filters={filters} updateFilters={updateFilters} resetFilters={resetFilters} />
          </SubHeaderRight>
      </SubHeader>

      <Page container="fluid">
        {error && <ErrorMessage error={error} />}
        {resources ? (
          <div className="accordion" id="educativeResourceAccordion">
            {Object.entries(
              resources.reduce((acc: Record<string, any[]>, resource: any) => {
                if (!acc[resource.tag]) acc[resource.tag] = [];
                acc[resource.tag].push(resource);
                return acc;
              }, {} as Record<string, any[]>)
            ).map(([tag, groupResources], idx) => {
              const tagLabel = TYPES_OF_EDUCATIVE_RESOURCES.find(t => t.value === tag)?.label || tag;
              const isOpen = openTags.includes(tag);
              return (
                <div className="accordion-item mb-2" key={tag}>
                  <h2 className="accordion-header" id={`heading-${tag}`}>
                    <button
                      className={`accordion-button mt-4 ${isOpen ? '' : 'collapsed'}`}
                      type="button"
                      onClick={() =>
                        setOpenTags((prev) =>
                          prev.includes(tag)
                            ? prev.filter((t) => t !== tag)
                            : [...prev, tag]
                        )
                      }
                      aria-expanded={isOpen}
                      aria-controls={`collapse-${tag}`}
                      style={{
                        fontWeight: 600,
                        borderRadius: isOpen ? "1.5rem" : "1.5rem",
                        backgroundColor: isOpen ? "#ffbb00" : "#fff",
                        color: isOpen ? "#fff" : "#000",
                        boxShadow: !isOpen ? "0 2px 8px rgba(0,0,0,0.04)" : "",
                        border: "1px solid #eee",
                        transition: "all 0.3s ease"
                      }}
                    >
                      {tagLabel}
                    </button>
                  </h2>
                  <div
                    id={`collapse-${tag}`}
                    className={`accordion-collapse collapse${isOpen ? ' show' : ''}`}
                    aria-labelledby={`heading-${tag}`}
                  >
                    <div className="accordion-body p-0">
                      <div className="row g-4" style={{ marginTop: "18px", marginBottom: "18px" }}>
                        {groupResources.map((resource: any) => {
                          const youtubeId = getYoutubeId(resource.youtubeUrl);
                          return (
                            <div className="col-12 col-sm-6 col-md-4 col-lg-3" key={resource.id}>
                              <Card stretch={false} className="h-100 video-card border-0 shadow-sm">
                                {resource.isVideo ? (
                                  <div
                                    className="video-thumbnail position-relative"
                                    onClick={() => setPlayingId(playingId === resource.id ? null : resource.id)}
                                    style={{ cursor: "pointer" }}
                                  >
                                    {playingId === resource.id && youtubeId ? (
                                      <iframe
                                        src={`https://www.youtube.com/embed/${youtubeId}?autoplay=1`}
                                        title={resource.title}
                                        frameBorder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowFullScreen
                                        className="w-100"
                                        height="200"
                                      />
                                    ) : (
                                      <div className="position-relative">
                                        <img
                                          src={`https://img.youtube.com/vi/${youtubeId}/hqdefault.jpg`}
                                          alt={resource.title}
                                          className="w-100"
                                          height={200}
                                          style={{ objectFit: "cover" }}
                                        />
                                        <span className="play-icon">▶</span>
                                      </div>
                                    )}
                                  </div>
                                ) : (
                                  <div className="web-resource p-3 d-flex flex-column justify-content-between h-100">
                                    <div>
                                      <h6 className="fw-semibold mb-1 text-truncate" title={resource.title}>
                                        {resource.title}
                                      </h6>
                                      <p className="text-muted small mb-2">{resource.description || " "}</p>
                                    </div>
                                    <a
                                      href={resource.youtubeUrl}
                                      target="_blank"
                                      rel="noopener noreferrer"
                                      className="btn btn-outline-primary btn-sm mt-auto"
                                      style={{ width: "fit-content" }}
                                    >
                                      Ir al recurso
                                    </a>
                                  </div>
                                )}
                                <div className="card-body p-3">
                                  {resource.isVideo && (
                                    <>
                                      <h6 className="fw-semibold mb-1 text-truncate" title={resource.title}>
                                        {resource.title}
                                      </h6>
                                      <p className="text-muted small mb-2 text-truncate">{resource.description || " "}</p>
                                    </>
                                  )}
                                  <div className="d-flex justify-content-between align-items-center">
                                    <small className="text-muted">
                                      {moment(resource.createdAt?.date).format("DD MMM YYYY")}
                                    </small>
                                    <div>
                                      {userCan("edit", "educative_resources") && (
                                        <Button
                                          color="link"
                                          size="sm"
                                          icon="Edit"
                                          onClick={() => navigate(`${resource.id}/edit`)}
                                        />
                                      )}
                                      {userCan("delete", "educative_resources") && (
                                        <Button
                                          color="link"
                                          size="sm"
                                          icon="Delete"
                                          className="text-danger"
                                          onClick={() =>
                                            handleConfirmationAlert({
                                              title: "Eliminar recurso educativo",
                                              text: "¿Estás seguro de que deseas eliminar el recurso?",
                                              icon: "warning",
                                              onConfirm: () => deleteResource(resource.id),
                                            })
                                          }
                                        />
                                      )}
                                    </div>
                                  </div>
                                </div>
                              </Card>
                            </div>
                          );
                        })}
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        ) : !error && <Loader />}
      </Page>
    </Fragment>
  );
};

export default EducativeResourceList;
