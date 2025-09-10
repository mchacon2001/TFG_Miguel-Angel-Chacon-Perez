<?php

namespace App\Controller\Public;

use App\Request\Public\RecoverPasswordRequest;
use App\Request\Public\SendEmailToRecoverPasswordRequest;
use App\Request\Public\UploadFileToServerRequest;
use App\Request\Public\RegisterUserRequest;
use App\Services\Public\PublicRequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;



class PublicController extends AbstractController
{

    public function __construct(
     protected PublicRequestService $publicRequestService
    )
    {

    }

    // -----------------------------------------------------------
    /**
     * EN: RECOVER THE CLIENT PASSWORD
     * ES: RECUPERAR LA CONTRASEÑA DEL CLIENTE
     *
     * @param SendEmailToRecoverPasswordRequest $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    // -----------------------------------------------------------
    #[Route('/send-email', name: 'api_send_email', methods: ["POST"])]
    public function sendEmail(SendEmailToRecoverPasswordRequest $request, MailerInterface $mailer): Response
    {
        return $this->publicRequestService->sendEmail($request, $mailer);
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: RECOVER THE CLIENT PASSWORD
     * ES: RECUPERAR LA CONTRASEÑA DEL CLIENTE
     *
     * @param RecoverPasswordRequest $request
     * @return Response
     */
    // -----------------------------------------------------------
    #[Route('/reset-password', name: 'api_reset_password', methods: ["POST"])]
    public function recoverPassword(RecoverPasswordRequest $request): Response
    {
        return $this->publicRequestService->recoverPassword($request);
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: UPLOAD FILE TO SERVER
     * ES: SUBIR ARCHIVO AL SERVIDOR
     *
     * @param UploadFileToServerRequest $request
     * @return Response
     */
    // -----------------------------------------------------------
    #[Route('/upload-to-server', name: 'api_upload_file', methods: ["POST"])]
    public function uploadFileToServerController(UploadFileToServerRequest $request): Response
    {
        return $this->publicRequestService->uploadFileToServer($request);
    }
    // -----------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: CREATE A NEW USER
     * ES: CREAR UN NUEVO USUARIO
     *
     * @param registerUserRequest $request
     * @return Response
     */

    // -----------------------------------------------------------
    #[Route('/register', name: 'api_register', methods: ["POST"])]
    public function registerUser(RegisterUserRequest $request, MailerInterface $mailer): Response
    {
        return $this->publicRequestService->registerUser($request, $mailer);
    }
    // -----------------------------------------------------------
}