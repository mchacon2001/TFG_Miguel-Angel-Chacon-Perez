<?php

namespace App\Services\User;

use App\Entity\User\User;
use App\Request\Role\ListRoleRequest;
use App\Request\User\CreateReportRequest;
use App\Request\User\DeleteImageUserRequest;
use App\Request\User\DeleteUserRequest;
use App\Request\User\EditUserRequest;
use App\Request\User\GetCalorieIntakeRequest;
use App\Request\User\GetMentalStatsRequest;
use App\Request\User\GetPhysicalStatsRequest;
use App\Request\User\GetUserRequest;
use App\Request\User\InsertImageUserRequest;
use App\Request\User\ListUsersRequest;
use App\Request\User\ResetPermissionsUserRequest;
use App\Request\User\EditPermissionsUserRequest;
use App\Request\User\CreateMentalStatsRequest;
use App\Request\User\CreatePhysicalStatsRequest;
use App\Services\Diet\DietService;
use App\Services\Document\DocumentService;
use App\Services\Routine\RoutineService;
use App\Services\User\UserHasRoutineService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Request\User\CreateUserRequest;
use App\Request\User\ToggleUserRequest;
use App\Request\User\AddRoleUserRequest;
use App\Utils\Classes\JWTHandlerService;
use App\Request\User\RemoveRoleUserRequest;
use App\Utils\Tools\MailService;
use App\Utils\Tools\Util;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Request\User\ChangePasswordUserRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;


class UserRequestService extends JWTHandlerService
{

    public function __construct(
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected DocumentService $documentService,
        protected RoleService $roleService,
        protected MailService $mailService,
        protected DietService $dietService,
        protected UserHasDietService $userHasDietService,
        protected RoutineService $routineService,
        protected UserHasRoutineService $userHasRoutineService
    )
    {
        parent::__construct( $token, $jwtManager);

    }

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE USER DATA
     * ES: PETICIÓN PARA OBTENER LOS DATOS DEL USUARIO
     *
     * @param GetUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function get(GetUserRequest $request): APIJsonResponse
    {
        $data = $this->userService->getUserById($request->userId, true);
        unset($data['password']);

        return new APIJsonResponse(
            $data,
            true,
            'Usuario seleccionado'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST USERS WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR USUARIOS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListUsersRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function list(ListUsersRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->userService->list($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de usuarios obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A USER WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UN USUARIO CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateUserRequest $request
     * @param MailerInterface $mailer
     * @return APIJsonResponse
     * @throws APIException
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    // -----------------------------------------------------------
    public function create(CreateUserRequest $request, MailerInterface $mailer): APIJsonResponse
    {
        $birthdate = DateTime::createFromFormat('Y-m-d', $request->birthdate);
        if (!$birthdate) {
            throw new APIException('La fecha de nacimiento no es válida', 400);
        }

        $userCreated = $this->userService->create(
            email: $request->email,
            password: $request->password,
            name: $request->name,
            targetWeight: $request->targetWeight,
            sex: $request->sex,
            birthdate: $birthdate,
            role: $request->roleId,
            toGainMuscle: $request->toGainMuscle,
            toLoseWeight: $request->toLoseWeight,
            toMaintainWeight: $request->toMaintainWeight,
            toImprovePhysicalHealth: $request->toImprovePhysicalHealth,
            toImproveMentalHealth: $request->toImproveMentalHealth,
            fixShoulder: $request->fixShoulder,
            fixKnees: $request->fixKnees,
            fixBack: $request->fixBack,
            rehab: $request->rehab
        );

        // Añadir entrada física inicial con cálculo de bodyFat y BMI
        if ($userCreated) {
            $weight = $request->weight;
            $height = $request->height;
            $height_m = $height / 100;
            $bodyFat = ($height_m > 0) ? $weight / ($height_m * $height_m) : null;
            $now = new DateTime();
            $age = $birthdate ? $birthdate->diff($now)->y : 0;
            $sex = strtolower($request->sex);
            if ($sex === 'male' || $sex === 'hombre' || $sex === 'm') {
                $bmi = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
            } else {
                $bmi = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
            }

            $this->assignDietsBasedOnUserGoals($userCreated, $request);
            $this->assignRoutinesBasedOnUserGoals($userCreated, $request);


            $this->userService->addPhysicalStats($userCreated, $height, $weight, $bodyFat, $bmi);
            $this->mailService->sendCredentialsEmail($userCreated, $request->password, $mailer);
        }

        return new APIJsonResponse(
            [],
            true,
            'Usuario creado con éxito'
        );

    }
    // -----------------------------------------------------------

    /**
     * Asignar dietas automáticamente basadas en los objetivos del usuario
     *
     * @param User $user
     * @param CreateUserRequest $request
     * @return void
     */
    private function assignDietsBasedOnUserGoals(User $user, CreateUserRequest $request): void
    {
        try {
            // Solo asignar si el usuario tiene al menos un objetivo activado
            $hasAnyDietGoal = $request->toGainMuscle || $request->toLoseWeight || $request->toMaintainWeight;
            
            if (!$hasAnyDietGoal) {
                return; // No diet goals set, no automatic assignment
            }

            // Crear un filtro para buscar dietas que coincidan con los objetivos del usuario
            $filterService = new FilterService($request);
            
            // Solo añadir filtros para objetivos que están activados
            if ($request->toGainMuscle) {
                $filterService->addFilter('toGainMuscle', true);
            }
            
            if ($request->toLoseWeight) {
                $filterService->addFilter('toLoseWeight', true);
            }

            if ($request->toMaintainWeight) {
                $filterService->addFilter('toMaintainWeight', true);
            }
            
            $matchingDiets = $this->dietService->listDietService($filterService);
            
            if (isset($matchingDiets['diets']) && is_array($matchingDiets['diets'])) {
                foreach ($matchingDiets['diets'] as $dietData) {
                    $diet = $this->dietService->getDietByIdSimple($dietData['id']);
                    if ($diet) {
                        try {
                            $this->userHasDietService->create($user, $diet);
                        } catch (Exception $e) {
                            error_log("Error assigning diet {$diet->getId()} to user {$user->getId()}: " . $e->getMessage());
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al asignar dietas automáticamente: " . $e->getMessage());
        }
    }

    /**
     * Asignar rutinas automáticamente basadas en los objetivos del usuario
     *
     * @param User $user
     * @param CreateUserRequest $request
     * @return void
     */
    private function assignRoutinesBasedOnUserGoals(User $user, CreateUserRequest $request): void
    {
        try {
            // Solo asignar si el usuario tiene al menos un objetivo activado
            $hasAnyGoal = $request->toGainMuscle || $request->toLoseWeight || $request->toMaintainWeight ||
                         $request->toImprovePhysicalHealth || $request->toImproveMentalHealth ||
                         $request->fixShoulder || $request->fixKnees || $request->fixBack || $request->rehab;
            
            if (!$hasAnyGoal) {
                return; // No goals set, no automatic assignment
            }

            $filterService = new FilterService($request);
            
            // Solo añadir filtros para objetivos que están activados
            if ($request->toGainMuscle) {
                $filterService->addFilter('toGainMuscle', true);
            }
            
            if ($request->toLoseWeight) {
                $filterService->addFilter('toLoseWeight', true);
            }

            if ($request->toMaintainWeight) {
                $filterService->addFilter('toMaintainWeight', true);
            }
            
            if ($request->toImprovePhysicalHealth) {
                $filterService->addFilter('toImprovePhysicalHealth', true);
            }
            
            if ($request->toImproveMentalHealth) {
                $filterService->addFilter('toImproveMentalHealth', true);
            }
            
            if ($request->fixShoulder) {
                $filterService->addFilter('fixShoulder', true);
            }
            
            if ($request->fixKnees) {
                $filterService->addFilter('fixKnees', true);
            }
            
            if ($request->fixBack) {
                $filterService->addFilter('fixBack', true);
            }
            
            if ($request->rehab) {
                $filterService->addFilter('rehab', true);
            }
            
            // Obtener las rutinas que coinciden con los objetivos
            $matchingRoutines = $this->routineService->listRoutinesService($filterService);
            
            // Asignar cada rutina encontrada al usuario
            if (isset($matchingRoutines['routines']) && is_array($matchingRoutines['routines'])) {
                foreach ($matchingRoutines['routines'] as $routineData) {
                    $routine = $this->routineService->getRoutineByIdSimple($routineData['id']);
                    if ($routine) {
                        try {
                            $this->userHasRoutineService->create($user, $routine);
                        } catch (Exception $e) {
                            error_log("Error assigning routine {$routine->getId()} to user {$user->getId()}: " . $e->getMessage());
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al asignar rutinas automáticamente: " . $e->getMessage());
        }
    }


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT A USER WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UN USUARIO CON LOS DATOS PROPORCIONADOS
     *
     * @param EditUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function edit(EditUserRequest $request): APIJsonResponse
    {
        $birthdate = DateTime::createFromFormat('Y-m-d', $request->birthdate);  
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->edit(
            user: $user,
            email: $request->email,
            name: $request->name,
            targetWeight: $request->targetWeight,
            sex: $request->sex,
            birthdate: $birthdate,
            role: $request->roleId,
            toGainMuscle: $request->toGainMuscle,
            toLoseWeight: $request->toLoseWeight,
            toMaintainWeight: $request->toMaintainWeight,
            toImprovePhysicalHealth: $request->toImprovePhysicalHealth,
            toImproveMentalHealth: $request->toImproveMentalHealth,
            fixShoulder: $request->fixShoulder,
            fixKnees: $request->fixKnees,
            fixBack: $request->fixBack,
            rehab: $request->rehab
        );

        return new APIJsonResponse(
            [],
            true,
            'Usuario editado con éxito'
        );
    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: INSERT A NEW IMAGE
     * ES: INSERTAR UNA NUEVA IMAGEN
     *
     * @param InsertImageUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function insertImage(InsertImageUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);
        $currentImg = $user->getProfileImg();

        $profileImg = $request->profileImg ? $this->documentService->uploadDocument($request->profileImg, User::UPLOAD_FILES_PATH) : $currentImg;

        $this->userService->insertImage(
            user: $user,
            profileImg: $profileImg
        );

        return new APIJsonResponse(
            [],
            true,
            'Imagen insertada con éxito'
        );
    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE AN IMAGE
     * ES: FUNCIÓN PARA ELIMINAR UNA IMAGEN
     *
     * @param DeleteImageUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function deleteImage(DeleteImageUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->deleteImage($user);

        return new APIJsonResponse(
            [],
            true,
            'Imagen eliminada con éxito'
        );
    }
    // ------------------------------------------------------------



    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO CHANGE THE PASSWORD OF A USER WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CAMBIAR LA CONTRASEÑA DE UN USUARIO CON LOS DATOS PROPORCIONADOS
     *
     * @param ChangePasswordUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function changePassword(ChangePasswordUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->changePassword(
            user: $user,
            password: $request->password
        );

        return new APIJsonResponse(
            [],
            true,
            'Contraseña cambiada con éxito'
        );
    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO ADD A ROLE TO A USER WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA AÑADIR UN ROL A UN USUARIO CON LOS DATOS PROPORCIONADOS
     *
     * @param AddRoleUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function addRole(AddRoleUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);
        $role = $this->roleService->getRoleById($request->roleId);

        $this->userService->addRole(
            user: $user,
            role: $role,
        );

        return new APIJsonResponse(
            [],
            true,
            'Rol añadido con éxito'
        );
    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO TOGGLE AN USER STATUS
     * ES: PETICIÓN PARA CAMBIAR EL ESTADO DE UN USUARIO
     *
     * @param ToggleUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function toggle(ToggleUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $toggleStatus = $this->userService->toggle($user);

        return new APIJsonResponse(
            [],
            true,
            'El usuario ha sido ' . $toggleStatus . ' con éxito.'
        );
    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A ROLE TO AN USER
     * ES: PETICIÓN PARA ELIMINAR UN ROL A UN USUARIO
     *
     * @param RemoveRoleUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function removeRole(RemoveRoleUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->removeRole(
            user: $user,
            userHasRole: $request->userRoleId
        );

        return new APIJsonResponse(
            [],
            true,
            'Rol eliminado con éxito'
        );
    }
    // ------------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT USER PERMISSIONS
     * ES: PETICIÓN PARA EDITAR LOS PERMISOS DEL USUARIO
     *
     * @param EditPermissionsUserRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -------------------------------------------------------
    public function editPermissions(EditPermissionsUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->editPermissions(
            user: $user,
            permissions: $request->permissions,
        );

        return new APIJsonResponse(
            [],
            true,
            'Permisos editados con éxito, recuerda cerrar sesión para que se apliquen los cambios'
        );
    }
    // -------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO RESET USER PERMISSIONS
     * ES: PETICIÓN PARA RESETEAR LOS PERMISOS DEL USUARIO
     *
     * @param ResetPermissionsUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function resetPermissions(ResetPermissionsUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->resetPermissions(
            user: $user,
        );

        return new APIJsonResponse(
            [],
            true,
            'Permisos reseteados con éxito'
        );
    }
    // -------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A USER
     * ES: PETICIÓN PARA ELIMINAR UN USUARIO
     *
     * @param DeleteUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function delete(DeleteUserRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        $this->userService->delete($user);

        return new APIJsonResponse(
            [],
            true,
            'Usuario eliminado con éxito'
        );
    }
    // -------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO LIST THE USER ROLES
     * ES: PETICIÓN PARA LISTAR LOS ROLES DEL USUARIO
     *
     * @param ListRoleRequest $request
     * @return APIJsonResponse
     */
    // -------------------------------------------------------
    public function listRoles(ListRoleRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->roleService->list($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de roles obtenido con exito'
        );
    }
    // -------------------------------------------------------

    /**
     * EN: REQUEST TO CREATE PHYSICAL STATS FOR A USER
     * ES: PETICIÓN PARA CREAR ESTADÍSTICAS FÍSICAS PARA UN USUARIO
     */
    public function createPhysicalStats(CreatePhysicalStatsRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        if (!$user) {
            throw new APIException('Usuario no encontrado', 404);
        }

        $weight = $request->weight;
        $height = $request->height;
        $height_m = $height / 100;
        $bodyFat = ($height_m > 0) ? $weight / ($height_m * $height_m) : null;
        $birthdate = $user->getBirthdate();
        $now = new DateTime();
        $age = $birthdate ? $birthdate->diff($now)->y : 0;
        $sex = strtolower($user->getSex());
        if ($sex === 'male' || $sex === 'hombre' || $sex === 'm') {
            $bmi = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        } else {
            $bmi = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        }

        $this->userService->addPhysicalStats(
            $user,
            $height,
            $weight,
            $bodyFat,
            $bmi
        );

        return new APIJsonResponse(
            [],
            true,
            'Estadísticas físicas creadas con éxito'
        );
    }

    /**
     * EN: REQUEST TO CREATE MENTAL STATS FOR A USER
     * ES: PETICIÓN PARA CREAR ESTADÍSTICAS MENTALES PARA UN USUARIO
     */
    public function createMentalStats(CreateMentalStatsRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        if (!$user) {
            throw new APIException('Usuario no encontrado', 404);
        }

        $this->userService->addMentalStats(
            $user,
            $request->mood,
            $request->sleepQuality
        );

        return new APIJsonResponse(
            [],
            true,
            'Estadísticas mentales creadas con éxito'
        );
    }
    // -------------------------------------------------------

    /**
     * EN: REQUEST TO GET MENTAL STATS FOR A USER
     * ES: PETICIÓN PARA OBTENER ESTADÍSTICAS MENTALES DE UN USUARIO
     */
    public function getMentalStats(GetMentalStatsRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        if (!$user) {
            throw new APIException('Usuario no encontrado', 404);
        }

        $mentalStats = $this->userService->getMentalStats($user);

        return new APIJsonResponse(
            $mentalStats,
            true,
            'Estadísticas mentales obtenidas con éxito'
        );
    }
    /**
     * EN: REQUEST TO GET PHYSICAL STATS FOR A USER
     * ES: PETICIÓN PARA OBTENER ESTADÍSTICAS FÍSICAS DE UN USUARIO
     */
    public function getPhysicalStats(GetPhysicalStatsRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        if (!$user) {
            throw new APIException('Usuario no encontrado', 404);
        }

        $physicalStats = $this->userService->getPhysicalStats($user);

        return new APIJsonResponse(
            $physicalStats,
            true,
            'Estadísticas físicas obtenidas con éxito'
        );
    }
    // -------------------------------------------------------

    /**
     * EN: REQUEST TO GENERATE USER REPORT
     * ES: PETICIÓN PARA GENERAR INFORME DE USUARIO
     */
    public function generateReport(CreateReportRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);
        
        if (!$user) {
            return new APIJsonResponse(
                [],
                false,
                'Usuario no encontrado'
            );
        }

        $reportData = $this->userService->generateUserReport($user, $request->period);
        $pdfContent = $this->userService->generatePDFReport($reportData, $user, $request->period);
        
        return new APIJsonResponse(
            ['pdf' => base64_encode($pdfContent)],
            true,
            'Informe generado con éxito'
        );
    }

    /**
     * EN: REQUEST TO GET CALORIE INTAKE FOR A USER
     * ES: PETICIÓN PARA OBTENER LA INGERENCIA CALÓRICA DE UN USUARIO
     */
    public function getCalorieIntake(GetCalorieIntakeRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($request->userId);

        if (!$user) {
            throw new APIException('Usuario no encontrado', 404);
        }

        $calorieIntake = $this->userService->getCalorieIntake($user);

        return new APIJsonResponse(
            $calorieIntake,
            true,
            'Ingerencia calórica obtenida con éxito'
        );
    }
    // -------------------------------------------------------
}