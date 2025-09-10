<?php

namespace App\Command;

use App\Services\User\UserService;
use App\Utils\Tools\Util;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:create-admin-user',
    description: 'Crea un usuario administrador de la plataforma para primer acceso',
    hidden: false
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(protected UserService $userService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'Nombre del usuario');
        $this->addOption('password', null, InputOption::VALUE_REQUIRED, 'Contraseña del usuario');
        $this->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email del usuario');
        $this->addOption('target_weight', null, InputOption::VALUE_REQUIRED, 'Peso objetivo del usuario');
        $this->addOption('birthday', null, InputOption::VALUE_REQUIRED, 'Fecha de nacimiento del usuario (YYYY-MM-DD)');
        $this->addOption('sex', null, InputOption::VALUE_REQUIRED, 'Sexo del usuario');
    }

    /**
     * @throws NonUniqueResultException
     */
    
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name         = $input->getOption('name');
        $pass         = $input->getOption('password');
        $email        = $input->getOption('email');
        $targetWeight = $input->getOption('target_weight');
        $birthday     = $input->getOption('birthday');
        $sex          = $input->getOption('sex');

        $validEmail = Util::validate_email($email);
        if(!$validEmail) {
            $output->writeln("El email introducido es inválido");
            return Command::FAILURE;
        }

        $birthdayDateTime = null;
        if ($birthday) {
            try {
                $birthdayDateTime = new \DateTime($birthday);
            } catch (\Exception $e) {
                $output->writeln("La fecha de nacimiento es inválida. Formato esperado: YYYY-MM-DD");
                return Command::FAILURE;
            }
        }

        $userExists =  $this->userService->getUserByEmail($email);
        
        if ($userExists) {
            $output->writeln('Error al crear el usuario. El usuario ' . $userExists->getEmail() . ' ya existe en la base de datos o el email es incorrecto.');
        } else {
            $this->userService->createAdminUser(
                $email,
                $pass,
                $name,
                $targetWeight, 
                $sex,
                $birthdayDateTime
            );
        }

        $output->writeln("Acción de creación de usuario administrador finalizada");

        return Command::SUCCESS;
    }

}
