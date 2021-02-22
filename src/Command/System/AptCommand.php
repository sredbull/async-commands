<?php

declare(strict_types=1);

namespace App\Command\System;

use App\Command\Command;
use App\Process\Apt\AutoremoveProcess;
use App\Process\Apt\InstallPackagesProcess;
use App\Process\Apt\AddKeyProcess;
use App\Process\Apt\RemovePackagesProcess;
use App\Process\Apt\AddRepositoryProcess;
use App\Process\Apt\UpdateProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AptCommand extends Command
{
    protected static $defaultName = 'install:apt';

    protected function configure(): void
    {
        $this
            ->setDescription('Install or remove configured packages.')
            ->setHelp('This command will install or remove packages configured in apt.yaml.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['keys', 'repos', 'install', 'remove']);
        $resolver->setAllowedTypes('keys', 'string[]');
        $resolver->setAllowedTypes('repos', 'string[]');
        $resolver->setAllowedTypes('install', 'string[]');
        $resolver->setAllowedTypes('remove', 'string[]');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->getConfig();

        $this->addAptKeys($output, $config['keys']);
        $this->addAptRepositories($output, $config['repos']);
        $this->runAptUpdate($output);
        $this->runAptRemove($output, $config['remove']);
        $this->runAptAutoremove($output);
        $this->runAptInstall($output, $config['install']);

        return Command::SUCCESS;
    }

    private function addAptKeys(OutputInterface $output, array $keys): void
    {
        foreach ($keys as $source => $url) {
            $process = AddKeyProcess::create(null, ['url' => $url]);
            $process->setSource($source);
            $process->runProcess($output);
        }
    }

    private function addAptRepositories(OutputInterface $output, array $repos): void
    {
        foreach ($repos as $source => $command) {
            $process = AddRepositoryProcess::create(null, ['command' => $command]);
            $process->setSource($source);
            $process->runProcess($output);
        }
    }

    private function runAptUpdate(OutputInterface $output): void
    {
        $process = UpdateProcess::create();
        $process->runProcess($output);
    }

    private function runAptRemove(OutputInterface $output, $packages): void
    {
        $process = RemovePackagesProcess::create(null, ['packages' => $packages]);
        $process->runProcess($output);
    }

    private function runAptAutoremove(OutputInterface $output): void
    {
        $process = AutoremoveProcess::create();
        $process->runProcess($output);
    }

    private function runAptInstall(OutputInterface $output, array $packages): void
    {
        $process = InstallPackagesProcess::create(null, ['packages' => $packages]);
        $process->runProcess($output);
    }
}
