<?php

namespace App\Services\Reports;

use App\Request\Reports\GenerateReportRequest;
use App\Utils\Classes\JWTHandlerService;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ReportsRequestService extends JWTHandlerService
{
    public function __construct(
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected Environment $twig,
        protected ReportsService $reportsService
    ) {
        parent::__construct($token, $jwtManager);
    }

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GENERATE COMPREHENSIVE REPORT
     * ES: PETICIÓN PARA GENERAR INFORME COMPLETO
     *
     * @param GenerateReportRequest $request
     * @return Response
     */
    // -----------------------------------------------------------
    public function generateReportRequestService(GenerateReportRequest $request): Response
    {
        // Get date range based on period
        $dateRange = $this->reportsService->getDateRange($request->period);
        
        // Collect all report data
        $reportData = $this->reportsService->gatherReportData($dateRange, $request->period);
        
        // Generate PDF
        $html = $this->twig->render('reports/comprehensive_report.html.twig', [
            'data' => $reportData,
            'period' => $request->period,
            'periodLabel' => $this->reportsService->getPeriodLabel($request->period),
            'dateRange' => $dateRange,
            'generatedAt' => new DateTime()
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = sprintf('Informe_%s_%s.pdf', 
            $this->reportsService->getPeriodLabel($request->period),
            (new DateTime())->format('Y-m-d')
        );

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: FUNCTION TO GET DATE RANGE BASED ON PERIOD
     * ES: FUNCIÓN PARA OBTENER RANGO DE FECHAS BASADO EN PERÍODO
     *
     * @param string $period
     * @return array
     */
    // -----------------------------------------------------------
    private function getDateRange(string $period): array
    {
        $now = new DateTime();
        
        return match($period) {
            'weekly' => [
                'from' => (clone $now)->modify('-7 days'),
                'to' => $now
            ],
            'monthly' => [
                'from' => (clone $now)->modify('-1 month'),
                'to' => $now
            ],
            'yearly' => [
                'from' => (clone $now)->modify('-1 year'),
                'to' => $now
            ]
        };
    }
    // -----------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: FUNCTION TO GET PERIOD LABEL IN SPANISH
     * ES: FUNCIÓN PARA OBTENER ETIQUETA DE PERÍODO EN ESPAÑOL
     *
     * @param string $period
     * @return string
     */
    // -----------------------------------------------------------
    private function getPeriodLabel(string $period): string
    {
        return match($period) {
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
            'yearly' => 'Anual'
        };
    }
    // -----------------------------------------------------------
}
