<?php

namespace App\Services\User;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentType;
use App\Entity\User\Role;
use App\Entity\User\User;
use App\Entity\User\UserHasDocument;
use App\Entity\User\UserHasRole;
use App\Entity\User\UserHasPhysicalStats;
use App\Entity\User\UserHasMentalStats;
use App\Repository\Document\DocumentTypeRepository;
use App\Repository\User\RoleRepository;
use App\Repository\User\UserHasDocumentRepository;
use App\Services\Document\DocumentService;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\EntityRepository;
use App\Entity\Permission\Permission;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\User\UserHasRoleRepository;
use App\Repository\Permission\PermissionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;

class UserService
{
    /**
     * @var UserRepository|EntityRepository
     */
    protected UserRepository|EntityRepository $userRepository;

    /**
     * @var RoleRepository|EntityRepository
     */
    protected RoleRepository|EntityRepository $roleRepository;

    /**
     * @var PermissionRepository|EntityRepository
     */
    protected PermissionRepository|EntityRepository $permissionRepository;

    /**
     * @var UserHasRoleRepository|EntityRepository
     */
    protected UserHasRoleRepository|EntityRepository $userHasRoleRepository;

    /**
     * @var UserHasDocumentRepository|EntityRepository
     */
    protected UserHasDocumentRepository|EntityRepository $userHasDocumentRepository;

    /**
     * @var DocumentTypeRepository|EntityRepository
     */
    protected DocumentTypeRepository|EntityRepository $documentTypeRepository;

    public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $encoder
    )
    {
        $this->userRepository = $em->getRepository(User::class);
        $this->roleRepository = $em->getRepository(Role::class);
        $this->permissionRepository = $em->getRepository(Permission::class);
        $this->userHasRoleRepository = $em->getRepository(UserHasRole::class);
        $this->userHasDocumentRepository = $em->getRepository(UserHasDocument::class);
        $this->documentTypeRepository = $em->getRepository(DocumentType::class);
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET USER BY ID
     * ES: SERVICIO PARA OBTENER UN USUARIO POR ID
     *
     * @param string $userId
     * @param bool $array
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getUserById(string $userId, ?bool $array = false): null|User|array
    {
        return $this->userRepository->findById($userId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET USER BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UN USUARIO POR ID (MÉTODO SIMPLE)
     *
     * @param string $userId
     * @param bool $array
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getUserByIdSimple(string $userId, ?bool $array = false): null|User|array
    {
        return $this->userRepository->findSimpleUserById($userId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE AN ADMIN USER
     * ES: SERVICIO PARA CREAR UN USUARIO ADMIN
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @return User|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function createAdminUser(string $email, string $password, string $name, string $targetWeight, string $sex, DateTime $birthdate): ?User
    {
        
        $role          = $this->roleRepository->findById(Role::ROLE_SUPER_ADMIN);
        $createdUser   = $this->userRepository->createBasicUser($this->encoder, $email, $password, $name, $targetWeight, $sex, $birthdate);

        $createdUser = $this->userRepository->addRoleToUser($createdUser, $role);
        return $this->userRepository->addPermissionsToUser($createdUser, $role->getPermissionsArray());
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET AN USER BY EMAIL
     * ES: SERVICIO PARA OBTENER UN USUARIO POR EMAIL
     *
     * @param string $email
     * @param bool|null $array
     * @return User|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getUserByEmail(string $email, ?bool $array = false): ?User
    {
        return $this->userRepository->findByEmail($email, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO UPDATE THE LAST LOGIN
     * ES: SERVICIO PARA ACTUALIZAR EL ÚLTIMO LOGIN
     *
     * @param User $user
     * @return User
     */
    // ------------------------------------------------------------------------
    public function updateLastLogin(User $user): User
    {
        return $this->userRepository->updateLastLogin($user);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO UPDATE THE LAST LOGIN
     * ES: SERVICIO PARA ACTUALIZAR EL ÚLTIMO LOGIN
     *
     * @param User $user
     * @return User|string|array
     */
    // ------------------------------------------------------------------------
    public function updateOnlineStatus(User $user): User|string|array
    {
        return $this->userRepository->updateOnlineStatus($user);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST USERS
     * ES: SERVICIO PARA LISTAR USUARIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        return $this->userRepository->list($filterService);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROLES BY USER
     * ES: SERVICIO PARA OBTENER LOS ROLES POR USUARIO
     *
     * @param string $userId
     * @return Role|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRolByUser(string $userId):?Role
    {
        return $this->userHasRoleRepository->getRolByUser($userId);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A USER
     * ES: SERVICIO PARA CREAR UN USUARIO
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $targetWeight
     * @param string $sex
     * @param DateTime $birthdate
     * @param string $role
     * @param string $permissions
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @throws NonUniqueResultException
     * @throws Exception
     * 
     * @return User|null
     */
    // ------------------------------------------------------------------------
    public function create(
        string $email,
        string $password,
        string $name,
        string $targetWeight,
        string $sex,
        DateTime $birthdate,
        string $role,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab

    ): ?User
    {
        /** @var ?Role $role */
        $role = $this->roleRepository->find($role);

        $permissions = [];

        if($role)
        {
            $permissions = $role->getPermissionsArray();
        }

        return $this->userRepository->create(
            encoder: $this->encoder,
            email: $email,
            pass: $password,
            name: $name,
            targetWeight: $targetWeight,
            sex: $sex,
            birthdate: $birthdate,
            role: $role,
            permissions: $permissions,
            toGainMuscle: $toGainMuscle,
            toLoseWeight: $toLoseWeight,
            toMaintainWeight: $toMaintainWeight,
            toImprovePhysicalHealth: $toImprovePhysicalHealth,
            toImproveMentalHealth: $toImproveMentalHealth,
            fixShoulder: $fixShoulder,
            fixKnees: $fixKnees,
            fixBack: $fixBack,
            rehab: $rehab
        );
    }

    /**
     * Crea una nueva entrada de stats físicos para el usuario.
     */
    public function addPhysicalStats(User $user, ?float $height, ?float $weight, ?float $bodyFat = null, ?float $bmi = null)
    {
        return $this->userRepository->addPhysicalStats($user, $height, $weight, $bodyFat, $bmi);
    }

    /**
     * Crea una nueva entrada de stats mentales para el usuario.
     */
    public function addMentalStats(User $user, float $mood, float $sleepQuality)
    {
        return $this->userRepository->addMentalStats($user, $mood, $sleepQuality);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A USER
     * ES: SERVICIO PARA EDITAR UN USUARIO
     *
     * @param User $user
     * @param string $email
     * @param string $name
     * @param string $targetWeight
     * @param string $sex
     * @param DateTime $birthdate
     * @param string $role
     * @return User|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function edit(User $user,
                         string $email,
                         string $name,
                         string $targetWeight,
                         string $sex,
                         DateTime $birthdate,
                        ?int $role,
                        bool $toGainMuscle,
                        bool $toLoseWeight,
                        bool $toMaintainWeight,
                        bool $toImprovePhysicalHealth,
                        bool $toImproveMentalHealth,
                        bool $fixShoulder,
                        bool $fixKnees,
                        bool $fixBack,
                        bool $rehab
                     ): ?User
    {
        $permissions = null;

            if($role != null)
            {
                $role = $this->roleRepository->find($role);
                $permissions = $role->getPermissionsArray();
            }

            $rolToDelete = $this->userHasRoleRepository->getRolByUser($user->getId());

            return $this->userRepository->edit(
                user: $user,
                email: $email,
                name: $name,
                targetWeight: $targetWeight,
                sex: $sex,
                birthdate: $birthdate,
                role: $role,
                permissions: $permissions,
                userHasRole: $rolToDelete,
                toGainMuscle: $toGainMuscle,
                toLoseWeight: $toLoseWeight,
                toMaintainWeight: $toMaintainWeight,
                toImprovePhysicalHealth: $toImprovePhysicalHealth,
                toImproveMentalHealth: $toImproveMentalHealth,
                fixShoulder: $fixShoulder,
                fixKnees: $fixKnees,
                fixBack: $fixBack,
                rehab: $rehab

            );
    }
    // ------------------------------------------------------------------------


    // ---------------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT THE USER IMAGE
     * ES: FUNCIÓN PARA EDITAR LA IMAGEN DEL USUARIO
     *
     * @param User $user
     * @param Document|null $profileImg
     * @return User|null
     */
    // ---------------------------------------------------------------------------------
    public function insertImage(User $user, ?Document $profileImg): ?User
    {
        return $this->userRepository->insertImage($user, $profileImg);
    }
    // ---------------------------------------------------------------------------------


    // ---------------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE THE USER IMAGE
     * ES: FUNCIÓN PARA ELIMINAR LA IMAGEN DEL USUARIO
     *
     * @param User $user
     * @return User|null
     */
    // ---------------------------------------------------------------------------------
    public function deleteImage(User $user): ?User
    {
        return $this->userRepository->removeImage($user);
    }
    // ---------------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CHANGE THE PASSWORD OF A USER
     * ES: SERVICIO PARA CAMBIAR LA CONTRASEÑA DE UN USUARIO
     *
     * @param User $user
     * @param string $password
     * @return User|null
     */
    // ------------------------------------------------------------------------
    public function changePassword(User $user, string $password): ?User
    {
        return $this->userRepository->changePassword(
            encoder: $this->encoder,
            user: $user,
            pass: $password
        );
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT THE PERMISSIONS OF A USER
     * ES: SERVICIO PARA EDITAR LOS PERMISOS DE UN USUARIO
     *
     * @param User $user
     * @param array $permissions
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function editPermissions(User $user, array $permissions): User|array|null
    {
        $permissions = $permissions ? $this->permissionRepository->findByIds($permissions) : [];

        $user = $this->userRepository->removePermissions(
            user: $user,
        );

        $user_edited =  $this->userRepository->addPermissionsToUser(
            user: $user,
            permissions: $permissions,
        );

        return $this->userRepository->findById($user_edited->getId(), true);
    }
    //------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO RESET THE PERMISSIONS OF A USER
     * ES: SERVICIO PARA RESETEAR LOS PERMISOS DE UN USUARIO
     *
     * @param User $user
     * @return User|array|bool|null
     */
    // ------------------------------------------------------------------------
    public function resetPermissions(User $user): User|array|null|bool
    {
        $defaultPermissions = $user->getUserRoles()[0]->getRole()->getPermissions();
        $permissions = [];

        foreach ($defaultPermissions as $permission)
        {
            $permissions[] = $permission->getPermission();
        }

        $user = $this->userRepository->removePermissions(
            user: $user,
        );

        $user_edited =  $this->userRepository->addPermissionsToUser(
            user: $user,
            permissions: $permissions,
        );

        return true;
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO ADD A ROLE TO A USER
     * ES: SERVICIO PARA AGREGAR UN ROL A UN USUARIO
     *
     * @param User $user
     * @param Role $role
     * @return User|null
     */
    // ------------------------------------------------------------------------
    public function addRole(User $user, Role $role): ?User
    {
        $userHasRoleExist = $this->userHasRoleRepository->findOneBy(['user' => $user, 'role' => $role]);

        if(!$userHasRoleExist)
        {
            return $this->userRepository->addRoleToUser(
                user: $user,
                role: $role,
            );
        }

        return null;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO REMOVE A ROLE OF A USER
     * ES: SERVICIO PARA ELIMINAR UN ROL DE UN USUARIO
     *
     * @param User $user
     * @param string $userHasRole
     * @return User|null
     */
    // ------------------------------------------------------------------------
    public function removeRole(User $user, string $userHasRole): ?User
    {
        $userHasRole = $userHasRole ? $this->userHasRoleRepository->find($userHasRole) : null;

        return $this->userRepository->removeRoleOfUser(
            $user,
            $userHasRole
        );
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO TOGGLE A USER STATUS
     * ES: SERVICIO PARA CAMBIAR EL ESTADO DE UN USUARIO
     *
     * @param User $user
     * @return User|string|null
     */
    // ------------------------------------------------------------------------
    public function toggle(User $user): User|string|null
    {
        return $this->userRepository->toggleUser(
            $user
        );
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A USER
     * ES: SERVICIO PARA ELIMINAR UN USUARIO
     *
     * @param User $user
     * @return User|null
     */
    // ------------------------------------------------------------------------
    public function delete(User $user): ?User
    {
        return $this->userRepository->remove($user);
    }
    // ------------------------------------------------------------------------


    // ----------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET PROJECT INFO TO DASHBOARD
     * ES: SERVICIO PARA OBTENER LA INFORMACION DE PROYECTOS PARA EL DASHBOARD
     *
     * @throws Exception
     */
    // ----------------------------------------------------------------------
    public function countUsersDashboardInfoService(FilterService $filterService): array
    {
        return $this->userRepository->list($filterService);
    }
    // ----------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL PHYSICAL STATS
     * ES: SERVICIO PARA OBTENER TODOS LOS STATS FÍSICOS
     *
     * @return array
     */
    // ----------------------------------------------------------------------
    public function getPhysicalStats(User $user): array
    {
        return $this->userRepository->getPhysicalStats($user);
    }
    // ----------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL MENTAL STATS
     * ES: SERVICIO PARA OBTENER TODOS LOS STATS MENTALES
     *
     * @return array
     */
    // ----------------------------------------------------------------------
    public function getMentalStats(User $user): array
    {
        return $this->userRepository->getMentalStats($user);
    }
    // ----------------------------------------------------------------------
    

    /**
     * EN: SERVICE TO GET USERS BY FLAGS
     * ES: SERVICIO PARA OBTENER USUARIOS POR FLAGS
     *
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return array
     */
    public function getUsersByFlags(
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): array
    {
        return $this->userRepository->findUsersByFlags(
            $toGainMuscle,
            $toLoseWeight,
            $toMaintainWeight,
            $toImprovePhysicalHealth,
            $toImproveMentalHealth,
            $fixShoulder,
            $fixKnees,
            $fixBack,
            $rehab
        );
    }

    /**
     * EN: GENERATE USER REPORT DATA
     * ES: GENERAR DATOS DE INFORME DE USUARIO
     */
    public function generateUserReport(User $user, string $period): array
    {
        $startDate = $this->getStartDateForPeriod($period);
        $endDate = new DateTime('now');

        return [
            'user' => $user,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'physicalStats' => $this->getPhysicalStatsForPeriod($user, $startDate, $endDate),
            'mentalStats' => $this->getMentalStatsForPeriod($user, $startDate, $endDate),
            'calorieIntake' => $this->getCalorieIntakeForPeriod($user, $startDate, $endDate),
            'exerciseDays' => $this->getExerciseDaysForPeriod($user, $startDate, $endDate),
            'exerciseDetails' => $this->getExerciseDetailsForPeriod($user, $startDate, $endDate)
        ];
    }

    /**
     * EN: GET START DATE FOR PERIOD
     * ES: OBTENER FECHA DE INICIO PARA PERÍODO
     */
    private function getStartDateForPeriod(string $period): DateTime
    {
        $date = new DateTime('now');
        
        switch ($period) {
            case 'weekly':
                $date->modify('-7 days');
                break;
            case 'monthly':
                $date->modify('-1 month');
                break;
            case 'yearly':
                $date->modify('-1 year');
                break;
        }
        
        return $date;
    }

    /**
     * EN: GET PHYSICAL STATS FOR PERIOD
     * ES: OBTENER ESTADÍSTICAS FÍSICAS PARA PERÍODO
     */
    private function getPhysicalStatsForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->userRepository->getPhysicalStatsForPeriod($user, $startDate, $endDate);
    }

    /**
     * EN: GET MENTAL STATS FOR PERIOD
     * ES: OBTENER ESTADÍSTICAS MENTALES PARA PERÍODO
     */
    private function getMentalStatsForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->userRepository->getMentalStatsForPeriod($user, $startDate, $endDate);
    }

    /**
     * EN: GET CALORIE INTAKE FOR PERIOD
     * ES: OBTENER INGESTA DE CALORÍAS PARA PERÍODO
     */
    private function getCalorieIntakeForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        $results = $this->userRepository->getCalorieIntakeForPeriod($user, $startDate, $endDate);
        
        // Formatear los datos para el informe
        return array_map(function($item) {
            return [
                'date' => $item['date'],
                'total_calories' => $item['total_calories'] ?? 0,
                'total_proteins' => $item['total_proteins'] ?? 0,
                'total_carbs' => $item['total_carbs'] ?? 0,
                'total_fats' => $item['total_fats'] ?? 0
            ];
        }, $results);
    }

    public function getCalorieIntake(User $user): array
    {
        return $this->userRepository->getCalorieIntake($user);
    }

    /**
     * EN: GET EXERCISE DAYS FOR PERIOD
     * ES: OBTENER DÍAS DE EJERCICIO PARA PERÍODO
     */
    private function getExerciseDaysForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->userRepository->getExerciseDaysForPeriod($user, $startDate, $endDate);
    }

    /**
     * EN: GET EXERCISE DETAILS FOR PERIOD
     * ES: OBTENER DETALLES DE EJERCICIOS PARA PERÍODO
     */
    private function getExerciseDetailsForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->userRepository->getExerciseDetailsForPeriod($user, $startDate, $endDate);
    }
    // ------------------------------------------------------------------------

    /**
     * EN: GENERATE PDF REPORT
     * ES: GENERAR INFORME PDF
     */
    public function generatePDFReport(array $reportData, User $user, string $period): string
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        $html = $this->generateReportHTML($reportData);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();
    }

    /**
     * EN: GENERATE REPORT HTML
     * ES: GENERAR HTML DEL INFORME
     */
    private function generateReportHTML(array $data): string
    {
        $user = $data['user'];
        $periodLabel = $data['period'] === 'weekly' ? 'Semanal' : 
                      ($data['period'] === 'monthly' ? 'Mensual' : 'Anual');
        
        // Preparar datos para las gráficas
        $weightData = [];
        $imcData = [];
        $weightLabels = [];
        foreach ($data['physicalStats'] as $stat) {
            $weightLabels[] = $stat['recordedAt']->format('d/m');
            $weightData[] = $stat['weight'];
            
            // Calcular IMC: peso / (altura^2) - siempre calculado dinámicamente
            if (isset($stat['height']) && $stat['height'] > 0) {
                $heightInMeters = $stat['height'] / 100; // Convertir cm a metros
                $calculatedImc = $stat['weight'] / ($heightInMeters * $heightInMeters);
                $imcData[] = round($calculatedImc, 1);
            } else {
                // Si no hay altura, no podemos calcular IMC
                $imcData[] = 0;
            }
        }

        $moodData = [];
        $sleepData = [];
        $mentalLabels = [];
        foreach ($data['mentalStats'] as $stat) {
            $mentalLabels[] = $stat['recordedAt']->format('d/m');
            $moodData[] = $stat['mood'];
            $sleepData[] = $stat['sleepQuality'];
        }

        // Agrupar ejercicios por nombre
        $groupedExercises = [];
        foreach ($data['exerciseDetails'] as $exercise) {
            $exerciseName = $exercise['exercise_name'];
            if (!isset($groupedExercises[$exerciseName])) {
                $groupedExercises[$exerciseName] = [];
            }
            $groupedExercises[$exerciseName][] = $exercise;
        }

        // Ordenar cada grupo por fecha (más antigua a más nueva)
        foreach ($groupedExercises as $exerciseName => $exercises) {
            usort($groupedExercises[$exerciseName], function($a, $b) {
                return $a['date'] <=> $b['date'];
            });
        }

        // Ordenar los ejercicios alfabéticamente
        ksort($groupedExercises);
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Informe <?= $periodLabel ?> - <?= htmlspecialchars($user->getName()) ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
                .header { text-align: center; margin-bottom: 30px; }
                .section { margin-bottom: 25px; page-break-inside: avoid; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .stats-grid { width: 100%; }
                .stats-grid > div { float: left; width: 48%; margin-right: 4%; }
                .stats-grid::after { content: ""; display: table; clear: both; }
                
                /* Gráficas CSS simples */
                .charts-container { 
                    width: 100%;
                    clear: both;
                }
                .chart-row {
                    width: 100%;
                    margin-bottom: 30px;
                    clear: both;
                }
                .chart-container { 
                    width: 49%; 
                    height: 350px; 
                    border: 1px solid #ddd; 
                    float: left;
                    margin-right: 2%;
                    padding: 15px;
                    box-sizing: border-box;
                    background: #f9f9f9;
                }
                .chart-container:nth-child(even) { margin-right: 0; }
                .chart-title { 
                    text-align: center; 
                    font-weight: bold; 
                    margin-bottom: 15px; 
                    color: #333;
                    font-size: 12px;
                }
                .simple-timeline {
                    background: white;
                    border: 1px solid #ccc;
                    padding: 20px;
                    height: 250px;
                }
                .timeline-header {
                    border-bottom: 2px solid #333;
                    padding-bottom: 10px;
                    margin-bottom: 15px;
                    font-weight: bold;
                    color: #666;
                    font-size: 10px;
                }
                .timeline-item {
                    border-bottom: 1px solid #eee;
                    padding: 8px 0;
                    display: table;
                    width: 100%;
                }
                .timeline-date {
                    display: table-cell;
                    width: 25%;
                    font-size: 9px;
                    color: #666;
                    vertical-align: middle;
                }
                .timeline-value {
                    display: table-cell;
                    width: 20%;
                    font-size: 10px;
                    font-weight: bold;
                    vertical-align: middle;
                }
                .timeline-bar {
                    display: table-cell;
                    width: 55%;
                    vertical-align: middle;
                    padding-left: 10px;
                }
                .bar-fill {
                    height: 15px;
                    border-radius: 3px;
                    position: relative;
                }
                .bar-fill.weight { background: #3498db; }
                .bar-fill.bmi { background: #e74c3c; }
                .bar-fill.mood { background: #f39c12; }
                .bar-fill.sleep { background: #9b59b6; }
                
                .trend-indicator {
                    margin-top: 15px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-left: 4px solid #3498db;
                    font-size: 10px;
                }
                .trend-up { border-color: #27ae60; color: #27ae60; }
                .trend-down { border-color: #e74c3c; color: #e74c3c; }
                .trend-stable { border-color: #f39c12; color: #f39c12; }
                
                .exercise-group {
                    margin-bottom: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    overflow: hidden;
                }
                .exercise-group-header {
                    background-color: #3498db;
                    color: white;
                    padding: 8px 12px;
                    font-weight: bold;
                    font-size: 11px;
                }
                .exercise-group-table {
                    margin: 0;
                }
                .exercise-group-table th {
                    background-color: #ecf0f1;
                    font-size: 9px;
                }
                .bmi-category {
                    font-size: 9px;
                    font-style: italic;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Informe <?= $periodLabel ?></h1>
                <h2><?= htmlspecialchars($user->getName()) ?></h2>
                <p>Período: <?= $data['startDate']->format('d/m/Y') ?> - <?= $data['endDate']->format('d/m/Y') ?></p>
            </div>

            <div class="section">
                <h3>Resumen de Estadísticas</h3>
                <div class="stats-grid">
                    <div>
                        <h4>Estadísticas Físicas</h4>
                        <p>Registros de peso: <?= count($data['physicalStats']) ?></p>
                        <?php if (!empty($data['physicalStats'])): ?>
                            <p>Peso inicial: <?= $data['physicalStats'][0]['weight'] ?? 'N/A' ?> kg</p>
                            <p>Peso actual: <?= end($data['physicalStats'])['weight'] ?? 'N/A' ?> kg</p>
                            <?php 
                            $currentImc = end($imcData);
                            if ($currentImc > 0):
                                // Categorías IMC según OMS
                                $imcCategory = '';
                                if ($currentImc < 18.5) $imcCategory = 'Bajo peso';
                                elseif ($currentImc < 25) $imcCategory = 'Peso normal';
                                elseif ($currentImc < 30) $imcCategory = 'Sobrepeso';
                                else $imcCategory = 'Obesidad';
                            ?>
                            <p>IMC actual: <?= number_format($currentImc, 1) ?> (<?= $imcCategory ?>)</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4>Estadísticas Mentales</h4>
                        <p>Registros de ánimo: <?= count($data['mentalStats']) ?></p>
                        <?php if (!empty($data['mentalStats'])): ?>
                            <?php
                            $avgMood = array_sum(array_column($data['mentalStats'], 'mood')) / count($data['mentalStats']);
                            $avgSleep = array_sum(array_column($data['mentalStats'], 'sleepQuality')) / count($data['mentalStats']);
                            ?>
                            <p>Ánimo promedio: <?= number_format($avgMood, 1) ?>/10</p>
                            <p>Sueño promedio: <?= number_format($avgSleep, 1) ?>/10</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($data['physicalStats']) || !empty($data['mentalStats'])): ?>
            <div class="section">
                <h3>Evolución de Indicadores</h3>
                <div class="charts-container">
                
                    <?php if (!empty($data['physicalStats'])): ?>
                    <div class="chart-row">
                        <!-- Timeline de Peso -->
                        <div class="chart-container">
                            <div class="chart-title">Evolución del Peso (kg)</div>
                            <div class="simple-timeline">
                                <div class="timeline-header">Fecha | Peso | Evolución Visual</div>
                                <?php 
                                $maxWeight = max($weightData);
                                $minWeight = min($weightData);
                                $rangeWeight = $maxWeight - $minWeight;
                                if ($rangeWeight == 0) $rangeWeight = 1;
                                
                                foreach ($weightData as $index => $weight): 
                                    $percentage = (($weight - $minWeight) / $rangeWeight) * 100;
                                    $barWidth = max($percentage, 10);
                                ?>
                                <div class="timeline-item">
                                    <div class="timeline-date"><?= $weightLabels[$index] ?></div>
                                    <div class="timeline-value"><?= number_format($weight, 1) ?> kg</div>
                                    <div class="timeline-bar">
                                        <div class="bar-fill weight" style="width: <?= $barWidth ?>%;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php 
                                $firstWeight = $weightData[0];
                                $lastWeight = end($weightData);
                                $weightChange = $lastWeight - $firstWeight;
                                $trendClass = $weightChange > 0 ? 'trend-up' : ($weightChange < 0 ? 'trend-down' : 'trend-stable');
                                $trendText = $weightChange > 0 ? 'Aumento' : ($weightChange < 0 ? 'Disminución' : 'Estable');
                                ?>
                                <div class="trend-indicator <?= $trendClass ?>">
                                    Tendencia: <?= $trendText ?> de <?= number_format(abs($weightChange), 1) ?> kg
                                </div>
                            </div>
                        </div>

                        <!-- Timeline de IMC -->
                        <div class="chart-container">
                            <div class="chart-title">Evolución del IMC (kg/m²)</div>
                            <div class="simple-timeline">
                                <div class="timeline-header">Fecha | IMC | Evolución Visual</div>
                                <?php 
                                $filteredImcData = array_filter($imcData);
                                if (!empty($filteredImcData)):
                                    $maxImc = max($filteredImcData);
                                    $minImc = min($filteredImcData);
                                    $rangeImc = $maxImc - $minImc;
                                    if ($rangeImc == 0) $rangeImc = 1;
                                    
                                    foreach ($imcData as $index => $imc): 
                                        if ($imc > 0):
                                            $percentage = (($imc - $minImc) / $rangeImc) * 100;
                                            $barWidth = max($percentage, 10);
                                ?>
                                <div class="timeline-item">
                                    <div class="timeline-date"><?= $weightLabels[$index] ?></div>
                                    <div class="timeline-value"><?= number_format($imc, 1) ?></div>
                                    <div class="timeline-bar">
                                        <div class="bar-fill bmi" style="width: <?= $barWidth ?>%;"></div>
                                    </div>
                                </div>
                                <?php 
                                        endif;
                                    endforeach; 
                                    
                                    $firstImc = reset($filteredImcData);
                                    $lastImc = end($filteredImcData);
                                    $imcChange = $lastImc - $firstImc;
                                    $trendClass = $imcChange > 0 ? 'trend-up' : ($imcChange < 0 ? 'trend-down' : 'trend-stable');
                                    $trendText = $imcChange > 0 ? 'Aumento' : ($imcChange < 0 ? 'Disminución' : 'Estable');
                                ?>
                                <div class="trend-indicator <?= $trendClass ?>">
                                    Tendencia: <?= $trendText ?> de <?= number_format(abs($imcChange), 1) ?> kg/m²
                                </div>
                                <?php else: ?>
                                <p style="text-align: center; color: #666; margin-top: 50px;">No hay datos suficientes para calcular el IMC</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($data['mentalStats'])): ?>
                    <div class="chart-row">
                        <!-- Timeline de Ánimo -->
                        <div class="chart-container">
                            <div class="chart-title">Evolución del Ánimo (1-10)</div>
                            <div class="simple-timeline">
                                <div class="timeline-header">Fecha | Ánimo | Evolución Visual</div>
                                <?php foreach ($moodData as $index => $mood): 
                                    $percentage = (($mood - 1) / 9) * 100;
                                    $barWidth = max($percentage, 10);
                                ?>
                                <div class="timeline-item">
                                    <div class="timeline-date"><?= $mentalLabels[$index] ?></div>
                                    <div class="timeline-value"><?= $mood ?>/10</div>
                                    <div class="timeline-bar">
                                        <div class="bar-fill mood" style="width: <?= $barWidth ?>%;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php 
                                $firstMood = $moodData[0];
                                $lastMood = end($moodData);
                                $moodChange = $lastMood - $firstMood;
                                $trendClass = $moodChange > 0 ? 'trend-up' : ($moodChange < 0 ? 'trend-down' : 'trend-stable');
                                $trendText = $moodChange > 0 ? 'Mejora' : ($moodChange < 0 ? 'Empeora' : 'Estable');
                                ?>
                                <div class="trend-indicator <?= $trendClass ?>">
                                    Tendencia: <?= $trendText ?> de <?= number_format(abs($moodChange), 1) ?> puntos
                                </div>
                            </div>
                        </div>

                        <!-- Timeline de Calidad del Sueño -->
                        <div class="chart-container">
                            <div class="chart-title">Evolución de la Calidad del Sueño (1-10)</div>
                            <div class="simple-timeline">
                                <div class="timeline-header">Fecha | Sueño | Evolución Visual</div>
                                <?php foreach ($sleepData as $index => $sleep): 
                                    $percentage = (($sleep - 1) / 9) * 100;
                                    $barWidth = max($percentage, 10);
                                ?>
                                <div class="timeline-item">
                                    <div class="timeline-date"><?= $mentalLabels[$index] ?></div>
                                    <div class="timeline-value"><?= $sleep ?>/10</div>
                                    <div class="timeline-bar">
                                        <div class="bar-fill sleep" style="width: <?= $barWidth ?>%;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php 
                                $firstSleep = $sleepData[0];
                                $lastSleep = end($sleepData);
                                $sleepChange = $lastSleep - $firstSleep;
                                $trendClass = $sleepChange > 0 ? 'trend-up' : ($sleepChange < 0 ? 'trend-down' : 'trend-stable');
                                $trendText = $sleepChange > 0 ? 'Mejora' : ($sleepChange < 0 ? 'Empeora' : 'Estable');
                                ?>
                                <div class="trend-indicator <?= $trendClass ?>">
                                    Tendencia: <?= $trendText ?> de <?= number_format(abs($sleepChange), 1) ?> puntos
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="section">
                <h3>Rutinas realizadas</h3>
                <p>Total de dias completados: <?= count($data['exerciseDays']) ?></p>
                <?php if (!empty($data['exerciseDays'])): ?>
                    <table>
                        <tr><th>Fecha</th><th>Rutina</th><th>Duración (min)</th></tr>
                        <?php foreach ($data['exerciseDays'] as $day): ?>
                            <tr>
                                <td><?= $day['date']->format('d/m/Y H:i') ?></td>
                                <td><?= htmlspecialchars($day['routine_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php 
                                    if (isset($day['endTime']) && $day['endTime'] !== null) {
                                        $diff = $day['date']->diff($day['endTime']);
                                        echo $diff->h * 60 + $diff->i;
                                    } else {
                                        echo 'En progreso';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>

            <div class="section">
                <h3>Detalles de ejercicios realizados</h3>
                <?php if (!empty($groupedExercises)): ?>
                    <?php foreach ($groupedExercises as $exerciseName => $exercises): ?>
                        <div class="exercise-group">
                            <div class="exercise-group-header">
                                <?= htmlspecialchars($exerciseName) ?> (<?= count($exercises) ?> registros)
                            </div>
                            <table class="exercise-group-table">
                                <tr><th>Fecha</th><th>Series</th><th>Repeticiones</th><th>Peso (kg)</th></tr>
                                <?php foreach ($exercises as $exercise): ?>
                                    <tr>
                                        <td><?= $exercise['date']->format('d/m/Y H:i') ?></td>
                                        <td><?= $exercise['sets'] ?></td>
                                        <td><?= $exercise['reps'] ?></td>
                                        <td><?= $exercise['weight'] ?? 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron ejercicios registrados en este período.</p>
                <?php endif; ?>
            </div>

            <div class="section">
                <h3>Ingestas diarias</h3>
                <?php if (!empty($data['calorieIntake'])): ?>
                    <table>
                        <tr><th>Fecha</th><th>Calorías Totales</th><th>Proteínas (g)</th><th>Carbohidratos (g)</th><th>Grasas (g)</th></tr>
                        <?php foreach ($data['calorieIntake'] as $intake): ?>
                            <tr>
                                <td><?= $intake['date'] ?></td>
                                <td><?= number_format($intake['total_calories'], 2) ?></td>
                                <td><?= number_format($intake['total_proteins'], 2) ?></td>
                                <td><?= number_format($intake['total_carbs'], 2) ?></td>
                                <td><?= number_format($intake['total_fats'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No se encontraron registros de ingesta calórica en este período.</p>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

}