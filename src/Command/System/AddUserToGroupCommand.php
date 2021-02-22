<?php

declare(strict_types=1);

namespace App\Command\System;

use App\Command\Command;
use App\Process\System\AddUserToGroupProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUserToGroupCommand extends Command
{
    protected static $defaultName = 'add:user-to-group';
    protected array $dependencies = [AddGroupCommand::class];

    protected function configure(): void
    {
        $this
            ->setDescription('Add configured users to a group.')
            ->setHelp('This command will add users to a group.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['user-groups']);
        $resolver->setAllowedTypes('user-groups', 'array[]');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
         foreach ($this->getConfigParameter('user-groups') as [$user, $group]) {
            $process = AddUserToGroupProcess::create(null, ['group' => $group, 'user' => $user]);
            $process->setSource(static::getDefaultName());
            $process->runProcess($output);
        }

        return Command::SUCCESS;
    }
}
