<?php

declare(strict_types=1);

namespace App\Command\System;

use App\Command\Command;
use App\Process\System\AddGroupProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddGroupCommand extends Command
{
    protected static $defaultName = 'add:group';

    protected function configure(): void
    {
        $this
            ->setDescription('Add configured groups.')
            ->setHelp('This command will add system groups.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['groups']);
        $resolver->setAllowedTypes('groups', 'string[]');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getConfigParameter('groups') as $group) {
            $process = AddGroupProcess::create(null, ['group' => $group]);
            $process->setSource(static::getDefaultName());
            $process->runProcess($output);
        }

        return Command::SUCCESS;
    }
}
