<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

#[AsCommand(
    name: 'app:create:user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $usePasswordHasher,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'The email of the user')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'The password of the user')
            ->addOption('firstName', null, InputOption::VALUE_REQUIRED, 'The first name of the user')
            ->addOption('lastName', null, InputOption::VALUE_REQUIRED, 'The last name of the user');
    }

    public function execute(InputInterface $input, OutputInterface $outputInterface): int
    {
        try {
            $email = $input->getOption('email');
            $password = $input->getOption('password');
            $firstName = $input->getOption('firstName');
            $lastName = $input->getOption('lastName');

            $user = new User();
            $user->email = $email;

            $user->password = $this->usePasswordHasher->hashPassword($user, $password);
            $user->firstName = $firstName;
            $user->lastName = $lastName;

            $this->em->persist($user);
            $this->em->flush();

            $outputInterface->writeln("Created user with email: {$user->email}");

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $outputInterface->writeln("Error creating user: {$exception->getMessage()}");

            return Command::FAILURE;
        }
    }
}
