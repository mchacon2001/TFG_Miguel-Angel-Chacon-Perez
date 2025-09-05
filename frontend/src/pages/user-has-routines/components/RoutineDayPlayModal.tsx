import { FC } from "react";
import Modal, { ModalHeader, ModalBody, ModalFooter, ModalTitle } from "../../../components/bootstrap/Modal";
import Button from "../../../components/bootstrap/Button";

interface RoutineDayPlayModalProps {
  isOpen: boolean;
  setIsOpen: (open: boolean) => void;
  routineDays: any[];
  handlePlayDay: (day: any) => void;
}

const RoutineDayPlayModal: FC<RoutineDayPlayModalProps> = ({
  isOpen,
  setIsOpen,
  routineDays,
  handlePlayDay,
}) => {
  return (
    <Modal isOpen={isOpen} setIsOpen={setIsOpen} size="md" titleId="seleccionar_dia_rutina">
      <ModalHeader setIsOpen={setIsOpen} className="p-4">
        <ModalTitle id="seleccionar_dia_rutina">Selecciona un día de la rutina</ModalTitle>
      </ModalHeader>
      <ModalBody className="px-4">
        {routineDays && routineDays.length > 0 ? (
          routineDays.map((day: any, idx: number) => (
            <div key={idx} className="mb-3">
              <div className="d-flex align-items-center justify-content-between mb-1">
                <span>
                  Día {day.number}
                </span>
                <Button
                  color="success"
                  isLight
                  onClick={() => handlePlayDay(day)}
                >
                  <span role="img" aria-label="play">▶️</span>
                </Button>
              </div>
              <ul className="mb-0 ps-3">
                {day.exercises.map((ex: any) => (
                  <li key={ex.id}>{ex.exercise?.name}</li>
                ))}
              </ul>
            </div>
          ))
        ) : (
          <div>No hay días en esta rutina.</div>
        )}
      </ModalBody>
      <ModalFooter className="px-4 pb-4">
        <Button type="button" color="secondary" onClick={() => setIsOpen(false)}>
          Cancelar
        </Button>
      </ModalFooter>
    </Modal>
  );
};

export default RoutineDayPlayModal;
