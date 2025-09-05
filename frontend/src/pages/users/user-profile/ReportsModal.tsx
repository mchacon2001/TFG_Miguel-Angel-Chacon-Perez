import React, { FC, useState } from 'react';
import Modal, { ModalBody, ModalHeader, ModalFooter } from '../../../components/bootstrap/Modal';
import Button from '../../../components/bootstrap/Button';
import FormGroup from '../../../components/bootstrap/forms/FormGroup';
import { CardTitle } from '../../../components/bootstrap/Card';
import { Assessment } from '../../../components/icon/material-icons';
import { ReportsService } from '../../../services/reports/reportsService';
import { toast } from 'react-toastify';
import Spinner from '../../../components/bootstrap/Spinner';

interface ReportsModalProps {
  isOpen: boolean;
  setIsOpen: (isOpen: boolean) => void;
  userId: string; // Agregar userId
}

const ReportsModal: FC<ReportsModalProps> = ({ isOpen, setIsOpen, userId }) => {
  const [selectedPeriod, setSelectedPeriod] = useState<'weekly' | 'monthly' | 'yearly'>('monthly');
  const [loading, setLoading] = useState(false);

  const reportsService = new ReportsService();

  const handleGenerateReport = async () => {
    setLoading(true);
    try {
      const response = await reportsService.generateUserReport(userId, selectedPeriod);
      
      // Get the response data
      const responseData = response.getResponseData();
      
      if (!responseData?.success || !responseData?.data?.pdf) {
        throw new Error('No se pudo obtener la respuesta del servidor');
      }
      
      // Decode base64 PDF content
      const pdfBase64 = responseData.data.pdf;
      const pdfBinary = atob(pdfBase64);
      const bytes = new Uint8Array(pdfBinary.length);
      for (let i = 0; i < pdfBinary.length; i++) {
        bytes[i] = pdfBinary.charCodeAt(i);
      }
      
      // Create blob and download PDF
      const blob = new Blob([bytes], { type: 'application/pdf' });
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      
      const periodLabel = selectedPeriod === 'weekly' ? 'Semanal' : 
                         selectedPeriod === 'monthly' ? 'Mensual' : 'Anual';
      link.download = `Informe_${periodLabel}_${new Date().toISOString().split('T')[0]}.pdf`;
      
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
      
      toast.success('Informe personalizado generado correctamente');
      setIsOpen(false);
    } catch (error) {
      toast.error('Error al generar el informe personalizado');
      console.error('Error generating report:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Modal isOpen={isOpen} setIsOpen={setIsOpen} size="md" titleId="reports-modal">
      <ModalHeader className="ms-2 p-4 gap-4">
        <Assessment fontSize="30px" color="rgba(0, 0, 0, 0.3)" />
        <CardTitle className="fs-3">Generar Informe</CardTitle>
        <Button className="btn-close fs-5 p-4" onClick={() => setIsOpen(false)} />
      </ModalHeader>
      <hr className="mt-0" />
      <ModalBody className="px-4">
        <FormGroup label="Selecciona el período del informe">
          <div className="d-flex flex-column gap-3">
            <div className="form-check">
              <input
                className="form-check-input"
                type="radio"
                name="reportPeriod"
                id="weekly"
                value="weekly"
                checked={selectedPeriod === 'weekly'}
                onChange={(e) => setSelectedPeriod(e.target.value as 'weekly')}
              />
              <label className="form-check-label" htmlFor="weekly">
                <strong>Semanal</strong> - Datos de los últimos 7 días
              </label>
            </div>
            <div className="form-check">
              <input
                className="form-check-input"
                type="radio"
                name="reportPeriod"
                id="monthly"
                value="monthly"
                checked={selectedPeriod === 'monthly'}
                onChange={(e) => setSelectedPeriod(e.target.value as 'monthly')}
              />
              <label className="form-check-label" htmlFor="monthly">
                <strong>Mensual</strong> - Datos del último mes
              </label>
            </div>
            <div className="form-check">
              <input
                className="form-check-input"
                type="radio"
                name="reportPeriod"
                id="yearly"
                value="yearly"
                checked={selectedPeriod === 'yearly'}
                onChange={(e) => setSelectedPeriod(e.target.value as 'yearly')}
              />
              <label className="form-check-label" htmlFor="yearly">
                <strong>Anual</strong> - Datos del último año
              </label>
            </div>
          </div>
        </FormGroup>
        
        <div className="alert alert-info mt-3">
          <strong>El informe personalizado incluirá:</strong>
          <ul className="mb-0 mt-2">
            <li>Gráficas de evolución del ánimo y calidad del sueño</li>
            <li>Gráfica de evolución del peso</li>
            <li>Gráfica del índice de masa corporal</li>
            <li>Gráfica de ingesta diaria de calorías</li>
            <li>Gráfica de días de ejercicio realizados</li>
            <li>Tabla detallada de ejercicios con pesos y repeticiones</li>
          </ul>
        </div>
      </ModalBody>
      <ModalFooter className="d-flex justify-content-end gap-2">
        <Button color="secondary" onClick={() => setIsOpen(false)} isDisable={loading}>
          Cancelar
        </Button>
        <Button color="primary" onClick={handleGenerateReport} isDisable={loading}>
          {loading ? <Spinner isSmall /> : 'Generar Informe'}
        </Button>
      </ModalFooter>
    </Modal>
  );
};

export default ReportsModal;
