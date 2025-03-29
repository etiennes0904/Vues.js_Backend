<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function in_array;

#[AsCommand(
    name: 'app:update:user:role',
    description: 'Update the role of a user'
)]
class UpdateRoleUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'The email of the user')
            ->addOption('role', null, InputOption::VALUE_REQUIRED, 'New role (ROLE_ADMIN, ROLE_USER)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $email = $input->getOption('email');
            $role = $input->getOption('role');

            if (!in_array($role, RoleEnum::ALL)) {
                $output->writeln("Invalid role: {$role}");

                return Command::FAILURE;
            }

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $output->writeln("Email not found: {$email}");

                return Command::FAILURE;
            }

            $user->roles = [$role];

            $this->em->persist($user);
            $this->em->flush();

            $output->writeln("Updated role for user with email: {$user->email}");

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $output->writeln("Error: {$exception->getMessage()}");

            return Command::FAILURE;
        }
    }
}
