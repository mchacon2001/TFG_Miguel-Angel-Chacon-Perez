import { FC } from "react";
import Modal, { ModalHeader, ModalBody, ModalFooter, ModalTitle } from "../../../components/bootstrap/Modal";
import Button from "../../../components/bootstrap/Button";

interface ActiveRoutineModalProps {
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
  activeRoutine: any;
  onContinue: () => void;
  onFinishAndStart: () => void;
}

const ActiveRoutineModal: FC<ActiveRoutineModalProps> = ({
  isOpen,
  setIsOpen,
  activeRoutine,
  onContinue,
  onFinishAndStart,
}) => {


  // Verificar si activeRoutine tiene datos válidos
  const hasValidData = activeRoutine && Object.keys(activeRoutine).length > 0;

  return (
    <Modal isOpen={isOpen} setIsOpen={setIsOpen} size="md" titleId="rutina_activa_modal">
      <ModalHeader setIsOpen={setIsOpen} className="p-4">
        <ModalTitle id="rutina_activa_modal">
          <i className="bi bi-exclamation-triangle text-warning me-2"></i>
          Rutina en Progreso
        </ModalTitle>
      </ModalHeader>
      <ModalBody className="px-4">
        <div className="alert alert-warning mb-3">
          <strong>¡Atención! Tienes una rutina activa sin finalizar </strong> 
        </div>
        
        {hasValidData ? (
          <div className="mb-3">
            <p className="mb-1">
              <strong>Nombre:</strong> {activeRoutine?.routine?.name || activeRoutine?.routines?.name || "No disponible"}
            </p>
            <p className="mb-1">
              <strong>Día:</strong> {activeRoutine?.day || "No disponible"}
            </p>
            <p className="mb-3">
              <strong>Iniciada:</strong>{" "}
              {activeRoutine?.startTime?.date 
                ? new Date(activeRoutine.startTime.date).toLocaleString()
                : activeRoutine?.created_at?.date
                ? new Date(activeRoutine.created_at.date).toLocaleString()
                : "Fecha no disponible"
              }
            </p>
          </div>
        ) : (
          <div className="mb-3">
            <div className="alert alert-info">
              <strong>Cargando datos de la rutina activa...</strong>
            </div>
            <pre>{JSON.stringify(activeRoutine, null, 2)}</pre>
          </div>
        )}

        <div className="border-top pt-3">
          <p className="text-muted mb-0">
            ¿Qué te gustaría hacer?
          </p>
        </div>
      </ModalBody>
      <ModalFooter className="px-4 pb-4 d-flex gap-2">
        <Button 
          type="button" 
          color="secondary" 
          onClick={() => setIsOpen(false)}
          className="flex-fill"
        >
          Cancelar
        </Button>
        <Button 
          type="button" 
          color="primary" 
          onClick={onContinue}
          className="flex-fill"
          isDisable={!hasValidData}
        >
          <i className="bi bi-play-circle me-1"></i>
          Continuar
        </Button>
        <Button 
          type="button" 
          color="warning" 
          onClick={onFinishAndStart}
          className="flex-fill"
          isDisable={!hasValidData}
        >
          <i className="bi bi-stop-circle me-1"></i>
          Finalizar y Empezar Nueva
        </Button>
      </ModalFooter>
    </Modal>
  );
};

export default ActiveRoutineModal;