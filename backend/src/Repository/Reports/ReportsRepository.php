<?php

namespace App\Repository\Reports;

use App\Utils\Storage\DoctrineStorableObject;

use DateTime;
use Doctrine\ORM\EntityRepository;

class ReportsRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET USER STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS DE USUARIOS
     *
     * @param array $dateRange
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getUserStatistics(array $dateRange): array
    {
        $qb = $this->_em->createQueryBuilder();
        
        // Total users - reset query builder for each query
        $qb = $this->_em->createQueryBuilder();
        $totalUsers = $qb->select('COUNT(u.id)')
            ->from('App\Entity\User\User', 'u')
            ->leftJoin('u.userRoles', 'ur')
            ->leftJoin('ur.role', 'r')
            ->where('r.id NOT IN (1, 2)') // Exclude admins
            ->getQuery()
            ->getSingleScalarResult();

        // New users in period - reset query builder
        $qb = $this->_em->createQueryBuilder();
        $newUsers = $qb->select('COUNT(u.id)')
            ->from('App\Entity\User\User', 'u')
            ->leftJoin('u.userRoles', 'ur2')
            ->leftJoin('ur2.role', 'r2')
            ->where('r2.id NOT IN (1, 2)')
            ->andWhere('u.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        // Active users (with recent login) - reset query builder
        $qb = $this->_em->createQueryBuilder();
        $activeUsers = $qb->select('COUNT(u.id)')
            ->from('App\Entity\User\User', 'u')
            ->leftJoin('u.userRoles', 'ur3')
            ->leftJoin('ur3.role', 'r3')
            ->where('r3.id NOT IN (1, 2)')
            ->andWhere('u.lastLogin BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total' => $totalUsers,
            'new' => $newUsers,
            'active' => $activeUsers,
            'activePercentage' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0
        ];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET DIET STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS DE DIETAS
     *
     * @param array $dateRange
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getDietStatistics(array $dateRange): array
    {
        // Total diets
        $qb = $this->_em->createQueryBuilder();
        $totalDiets = $qb->select('COUNT(d.id)')
            ->from('App\Entity\Diet\Diet', 'd')
            ->getQuery()
            ->getSingleScalarResult();

        // New diets
        $qb = $this->_em->createQueryBuilder();
        $newDiets = $qb->select('COUNT(d.id)')
            ->from('App\Entity\Diet\Diet', 'd')
            ->where('d.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        // Most popular diets
        $qb = $this->_em->createQueryBuilder();
        $popularDiets = $qb->select('d.name, COUNT(uhd.id) as assignments')
            ->from('App\Entity\Diet\Diet', 'd')
            ->leftJoin('d.userDiets', 'uhd')
            ->where('uhd.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->groupBy('d.id')
            ->orderBy('assignments', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        return [
            'total' => $totalDiets,
            'new' => $newDiets,
            'popular' => $popularDiets
        ];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ROUTINE STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS DE RUTINAS
     *
     * @param array $dateRange
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getRoutineStatistics(array $dateRange): array
    {
        // Total routines
        $qb = $this->_em->createQueryBuilder();
        $totalRoutines = $qb->select('COUNT(r.id)')
            ->from('App\Entity\Routine\Routine', 'r')
            ->getQuery()
            ->getSingleScalarResult();

        // New routines
        $qb = $this->_em->createQueryBuilder();
        $newRoutines = $qb->select('COUNT(r.id)')
            ->from('App\Entity\Routine\Routine', 'r')
            ->where('r.createdAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total' => $totalRoutines,
            'new' => $newRoutines
        ];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET PHYSICAL STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS FÍSICAS
     *
     * @param array $dateRange
     * @return array
     */
    // ------------------------------------------------------------------------
    /* public function getPhysicalStatistics(array $dateRange): array
    {
        // Total records
        $qb = $this->_em->createQueryBuilder();
        $totalRecords = $qb->select('COUNT(ps.id)')
            ->from('App\Entity\User\UserHasPhysicalStats', 'ps')
            ->where('ps.recordedAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        // Average weight
        $qb = $this->_em->createQueryBuilder();
        $avgWeight = $qb->select('AVG(ps.weight)')
            ->from('App\Entity\User\UserHasPhysicalStats', 'ps')
            ->where('ps.recordedAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalRecords' => $totalRecords,
            'averageWeight' => $avgWeight ? round($avgWeight, 2) : 0
        ];
    } */
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET MENTAL STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS MENTALES
     *
     * @param array $dateRange
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getMentalStatistics(array $dateRange): array
    {
        // Total records
        $qb = $this->_em->createQueryBuilder();
        $totalRecords = $qb->select('COUNT(ms.id)')
            ->from('App\Entity\User\UserHasMentalStats', 'ms')
            ->where('ms.recordedAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        // Average mood
        $qb = $this->_em->createQueryBuilder();
        $avgMood = $qb->select('AVG(ms.mood)')
            ->from('App\Entity\User\UserHasMentalStats', 'ms')
            ->where('ms.recordedAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        // Average sleep
        $qb = $this->_em->createQueryBuilder();
        $avgSleep = $qb->select('AVG(ms.sleepQuality)')
            ->from('App\Entity\User\UserHasMentalStats', 'ms')
            ->where('ms.recordedAt BETWEEN :from AND :to')
            ->setParameter('from', $dateRange['from'])
            ->setParameter('to', $dateRange['to'])
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalRecords' => $totalRecords,
            'averageMood' => $avgMood ? round($avgMood, 2) : 0,
            'averageSleep' => $avgSleep ? round($avgSleep, 2) : 0
        ];
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET GOAL STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS DE OBJETIVOS
     *
     * @param array $dateRange
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getGoalStatistics(array $dateRange): array
    {
        $qb = $this->_em->createQueryBuilder();
        $goalCounts = $qb->select('
                SUM(CASE WHEN u.toGainMuscle = true THEN 1 ELSE 0 END) as gainMuscle,
                SUM(CASE WHEN u.toLoseWeight = true THEN 1 ELSE 0 END) as loseWeight,
                SUM(CASE WHEN u.toMaintainWeight = true THEN 1 ELSE 0 END) as maintainWeight,
                SUM(CASE WHEN u.toImprovePhysicalHealth = true THEN 1 ELSE 0 END) as physicalHealth,
                SUM(CASE WHEN u.toImproveMentalHealth = true THEN 1 ELSE 0 END) as mentalHealth
            ')
            ->from('App\Entity\User\User', 'u')
            ->leftJoin('u.userRoles', 'ur')
            ->leftJoin('ur.role', 'r')
            ->where('r.id NOT IN (1, 2)')
            ->getQuery()
            ->getSingleResult();

        return $goalCounts;
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ACTIVITY STATISTICS
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS DE ACTIVIDAD
     *
     * @param array $dateRange
     * @param string $period
     * @return array
     */
    // ------------------------------------------------------------------------
    public function getActivityStatistics(array $dateRange, string $period): array
    {
        // Implementation for activity trends over time
        return [
            'dailyActivity' => [],
            'peakHours' => [],
            'trends' => []
        ];
    }
    // ------------------------------------------------------------------------
}
