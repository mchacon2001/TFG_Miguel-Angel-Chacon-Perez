<?php

namespace App\Services\Public;

use App\Request\Public\RecoverPasswordRequest;
use App\Request\Public\SendEmailToRecoverPasswordRequest;
use App\Request\Public\UploadFileToServerRequest;
use App\Request\Public\RegisterUserRequest;
use App\Utils\Classes\JWTHandlerService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\APIJsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\User\UserService;
use App\Utils\Tools\MailService;
use App\Services\Public\PublicService;
use DateTime;


use Doctrine\ORM\NonUniqueResultException;

class PublicRequestService extends JWTHandlerService
{

    public function __construct(
        protected PublicService            $publicService,
        protected Security                 $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected MailService $mailService,
        protected UserService $userService

    )
    {
        parent::__construct($token, $jwtManager);

    }


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO RECOVER THE CLIENT PASSWORD
     * ES: PETICIÓN PARA RECUPERAR LA CONTRASEÑA DEL CLIENTE
     *
     * @param SendEmailToRecoverPasswordRequest $request
     * @param MailerInterface $mailer
     * @return APIJsonResponse
     * @throws TransportExceptionInterface
     */
    // ------------------------------------------------------------
    public function sendEmail(SendEmailToRecoverPasswordRequest $request, MailerInterface $mailer): APIJsonResponse
    {

        $result = $this->publicService->sendEmail($request->email, $mailer);

        return new APIJsonResponse(
            data: 'success',
            success: true,
            message: 'Se ha enviado un correo electrónico con las instrucciones para recuperar la contraseña.'
        );

    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO RECOVER THE USER PASSWORD
     * ES: PETICIÓN PARA RECUPERAR LA CONTRASEÑA DEL USUARIO
     *
     * @param RecoverPasswordRequest $request
     * @return APIJsonResponse
     */
    // ------------------------------------------------------------
    public function recoverPassword(RecoverPasswordRequest $request): APIJsonResponse
    {

        if($request->password === $request->password_confirmation)
        {
            $this->publicService->recoverPassword($request->query_token, $request->password);

            return new APIJsonResponse(
                data: [],
                success: true,
                message: 'Se ha cambiado la contraseña correctamente.'
            );
        }

        return new APIJsonResponse(
            data: [],
            success: false,
            message: 'Las contraseñas no coinciden.'
        );
    }
    // ------------------------------------------------------------


    // ------------------------------------------------------------
    public function uploadFileToServer(UploadFileToServerRequest $request): APIJsonResponse
    {
        $file = $request->file;

        if($request->apiKey != '59acb9c9-dbbf-4b48-b83a-6ec45611d2f6')
        {
            return new APIJsonResponse(
                data: [],
                success: false,
                message: 'No tienes permisos para subir archivos.'
            );
        }

        $file->move('../resources/serverDatabase/', $file->getClientOriginalName());

        return new APIJsonResponse(
            data: [],
            success: true,
            message: 'El archivo se ha subido correctamente.'
        );
    }
    // ------------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A USER WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UN USUARIO CON LOS DATOS PROPORCIONADOS
     *
     * @param registerUserRequest $request
     * @param MailerInterface $mailer
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    // -----------------------------------------------------------
    public function registerUser(RegisterUserRequest $request, MailerInterface $mailer): APIJsonResponse

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
            role: '3',
            toGainMuscle: $request->toGainMuscle,
            toLoseWeight: $request->toLoseWeight,
            toMaintainWeight: $request->toMaintainWeight,
            toImprovePhysicalHealth: $request->toImprovePhysicalHealth,
            toImproveMentalHealth: $request->toImproveMentalHealth,
            fixShoulder: $request->fixShoulder,
            fixKnees: $request->fixKnees,
            fixBack: $request->fixBack,
            rehab: $request->rehab,
        );

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
}