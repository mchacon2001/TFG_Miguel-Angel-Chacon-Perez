<?php

namespace App\Utils\Tools;


use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;


class MailService
{
    protected MailerInterface $mailer;

    protected UserRepository|EntityRepository $userRepository;

    public function __construct(EntityManagerInterface $em,
                                protected KernelInterface $kernel,
                                ParameterBagInterface $parameterBag,
                                MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // --------------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO SEND CREDENTIALS TO USER BY EMAIL
     * ES: FUNCIÓN PARA ENVIAR CREDENCIALES AL USUARIO POR EMAIL
     *
     * @param User $user
     * @param string $password
     * @param MailerInterface $mailer
     * @return bool
     * @throws APIException
     * @throws TransportExceptionInterface
     */
    // --------------------------------------------------------------------------------
    public function sendCredentialsEmail(User $user, string $password, MailerInterface $mailer): bool
    {
        if(!$user->getEmail())
        {
            throw new APIException("El usuario no tiene un correo electrónico asociado, por favor introduce uno para continuar", 404);
        }

        $email = (new TemplatedEmail())
            ->from('miguel20072001@gmail.com')
            ->to($user->getEmail())
            ->subject('¡Bienvenido a BrainyGym!')
            ->htmlTemplate('emails/send_credentials_email.html.twig')
            ->context([
                'correo' => $user->getEmail(),
                'access_key' => $password
            ]);

        $mailer->send($email);

        return "Email enviado correctamente";
    }
    // --------------------------------------------------------------------------------
}