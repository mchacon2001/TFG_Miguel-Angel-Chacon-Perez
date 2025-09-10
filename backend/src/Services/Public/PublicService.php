<?php

namespace App\Services\Public;

use AllowDynamicProperties;
use App\Entity\User\User;
use App\Entity\User\Role;
use App\Kernel;
use App\Repository\User\UserRepository;
use App\Repository\User\RoleRepository;
use App\Services\Document\DocumentService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;





#[AllowDynamicProperties] class PublicService
{
    protected UserRepository|EntityRepository $userRepository;
    protected RoleRepository|EntityRepository $roleRepository;

    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
        protected UrlGeneratorInterface $urlGenerator,
        protected Kernel $kernel
    )
    {
        $this->userRepository = $em->getRepository(User::class);
        $this->roleRepository = $em->getRepository(Role::class);
    }


    // --------------------------------------------------------------------------------
    /**
     * EN: SERVICE TO RECOVER A PASSWORD
     * ES: SERVICIO PARA RECUPERAR UNA CONTRASEÑA
     *
     * @param string $email
     * @param MailerInterface $mailer
     * @return string|bool|null
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    // --------------------------------------------------------------------------------
    public function sendEmail(string $email, MailerInterface $mailer): null|string|bool
    {
        $user = $email ? $this->userRepository->findOneBy(['email' => $email]) : null;

        if($user)
        {
            $token = bin2hex(random_bytes(32));
            $this->userRepository->assignHash($user, $token);

            $resetPasswordUrl = $_ENV['RESET_PASSWORD_URL'].'?token='.$token;

            $email = (new TemplatedEmail())
                ->from('miguel20072001@gmail.com')
                ->to($user->getEmail())
                ->subject('Recupera tu contraseña')
                ->htmlTemplate('emails/reset_user_password_email.html.twig')
                ->context([
                    'resetPasswordUrl' => $resetPasswordUrl
                ]);

            $mailer->send($email);

            return true;
        }

        return false;
    }
    // --------------------------------------------------------------------------------


    // --------------------------------------------------------------------------------
    /**
     * EN: SERVICE TO RECOVER A PASSWORD
     * ES: SERVICIO PARA RECUPERAR UNA CONTRASEÑA
     *
     * @param string $token
     * @param string $password
     * @param string|null $email
     * @return string|null
     */
    // --------------------------------------------------------------------------------
    public function recoverPassword(string $token, string $password, ?string $email = null): ?string
    {
        $user = $token ? $this->userRepository->findOneBy(['temporalHash' => $token]) : null;

        if($user)
        {
            $this->userRepository->changePassword($this->encoder, $user, $password);
            $this->userRepository->removeHash($user);

            return "Contraseña actualizada";
        }

        return "El usuario introducido no existe, compruebe si ha introducido el correo electronico correctamente y vuelve a intentarlo";
    }
    // --------------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO REGISTER A USER WITH ROLE_USER
     * ES: SERVICIO PARA REGISTRAR UN USUARIO CON ROL_USER
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @param float $targetWeight
     * @param string $sex
     * @param DateTime $birthdate
     * @param string $role
     * @return User|null
     */
    // ------------------------------------------------------------------------
    public function register(
        string $email,
        string $password,
        string $name,
        float $targetWeight,
        string $sex,
        DateTime $birthdate,
        string $role,
        int $height = 0,
        float $weight = 0.0
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
        );
    }
    // ------------------------------------------------------------------------
}