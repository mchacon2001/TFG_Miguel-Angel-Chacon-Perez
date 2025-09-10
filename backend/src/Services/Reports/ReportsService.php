<?php

namespace App\Services\Reports;

use App\Repository\Reports\ReportsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ReportsService
{
    /**
     * @var ReportsRepository|EntityRepository
     */
    protected ReportsRepository|EntityRepository $reportsRepository;

    public function __construct(
        protected EntityManagerInterface $em
    ) {
        $this->reportsRepository = $em->getRepository('App\Entity\User\User'); // Temporary, will use a proper reports entity if needed
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GATHER ALL REPORT DATA
     * ES: SERVICIO PARA RECOPILAR TODOS LOS DATOS DEL INFORME
     *
     * @param array $dateRange
     * @param string $period
     * @return array
     */
    // ------------------------------------------------------------------------
    public function gatherReportData(array $dateRange, string $period): array
    {
        // Create a temporary repository instance for reports
        $reportsRepo = new ReportsRepository($this->em, $this->em->getClassMetadata('App\Entity\User\User'));
        
        return [
            'userStats' => $reportsRepo->getUserStatistics($dateRange),
            'dietStats' => $reportsRepo->getDietStatistics($dateRange),
            'routineStats' => $reportsRepo->getRoutineStatistics($dateRange),
            'physicalStats' => $reportsRepo->getPhysicalStatistics($dateRange),
            'mentalStats' => $reportsRepo->getMentalStatistics($dateRange),
            'goalStats' => $reportsRepo->getGoalStatistics($dateRange),
            'activityStats' => $reportsRepo->getActivityStatistics($dateRange, $period)
        ];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET DATE RANGE BASED ON PERIOD
     * ES: SERVICIO PARA OBTENER RANGO DE FECHAS BASADO EN PERÍODO
     *
     * @param string $period
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getDateRange(string $period): array
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
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET PERIOD LABEL IN SPANISH
     * ES: SERVICIO PARA OBTENER ETIQUETA DE PERÍODO EN ESPAÑOL
     *
     * @param string $period
     * @return string
     */
    // ------------------------------------------------------------------------
    public function getPeriodLabel(string $period): string
    {
        return match($period) {
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
            'yearly' => 'Anual'
        };
    }
    // ------------------------------------------------------------------------
}
