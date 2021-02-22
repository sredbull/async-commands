<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Process\Dependency;
use App\Model\Process\DependencyCollection;
use App\Model\Process\ListenerTrait;
use App\Model\Process\ProcessCollection;
use App\Process\CommandProcess;
use App\Process\Process;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateCommand extends Command
{
    use ListenerTrait, ProjectPathsTrait;

    protected static $defaultName = 'update';
    protected static ?string $commandFilter = 'webplatform';

    protected function configure(): void
    {
        $this
            ->setDescription('Update your webplatform.')
            ->setHelp('This command will update your webplatform.')
            ->addOption(
                'no-clear',
                'ncl',
                InputOption::VALUE_NONE,
                'Don\'t clear the var, vendor and node_modules directories'
            )
            ->addOption(
                'no-composer',
                'nco',
                InputOption::VALUE_NONE,
                'Don\'t run composer install'
            )
            ->addOption(
                'no-build',
                'nbu',
                InputOption::VALUE_NONE,
                'Don\'t build the new docker images'
            )
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['path', 'excluded_paths']);
        $resolver->setAllowedTypes('path', 'string');
        $resolver->setAllowedTypes('excluded_paths', 'string[]');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->startProcessesForProjects($input, $output);

        return self::SUCCESS;
    }

    private function startProcessesForProjects(InputInterface $input, OutputInterface $output): void
    {
        $this->startProcessesListener(
            $this->getActiveProcesses(
                $this->getProjectPaths(
                    $this->getConfigParameter('path'),
                    $this->getConfigParameter('excluded_paths')
                ),
                $this->getExcludedTags($input)
            ),
            new SymfonyStyle($input, $output)
        );
    }

    private function getActiveProcesses(array $projectPaths, array $excludedTags): ProcessCollection
    {
        $commands = $this->getCommands('webplatform', $excludedTags);
        $processCollection = new ProcessCollection();
        foreach ($projectPaths['paths'] as $projectPath) {
            $dependencyCollection = new DependencyCollection();
            /** @var Command $command */
            foreach ($commands as $command) {
                $process = $command::createProcess(array_merge($command->getConfig(), ['cwd' => $projectPath]));
                if ($command->hasDependencies() === false) {
                    $process->start();
                } else {
                    $dependencyCollection->add(new Dependency($process, $command->getDependencies()));
                }

                $dependencyCollection->getProcessCollection()->add($process);
                $processCollection->add($process);
            }

            $this->computeProcessDependencies($dependencyCollection);
        }

        return $processCollection;
    }

    private function getExcludedTags(InputInterface $input): array
    {
        $excludedTags = [];
        if ($input->getOption('no-clear') === true) {
            $excludedTags[] = 'no-clear';
        }
        if ($input->getOption('no-composer') === true) {
            $excludedTags[] = 'no-composer';
        }
        if ($input->getOption('no-build') === true) {
            $excludedTags[] = 'no-build';
        }

        return $excludedTags;
    }

    private function computeProcessDependencies(DependencyCollection $dependencyCollection): void
    {
        /** @var Dependency $dependencyModel */
        foreach ($dependencyCollection as $dependencyModel) {
            foreach ($dependencyModel->getDependencies() as $dependency) {
                $this->addProcessDependency(
                    $dependencyModel->getProcess(),
                    $dependencyCollection->getProcessCollection(),
                    $dependency
                );
            }
        }
    }

    private function addProcessDependency(
        Process $process,
        ProcessCollection $processCollection,
        string $dependency
    ): ?Process {
        /** @var CommandProcess $processModel */
        foreach ($processCollection as $processModel) {
            if ($processModel->getCommandByName($processModel->getCommandName()) !== $dependency) {
                continue;
            }

            $process->addDependency($processModel);
        }

        return null;
    }
}
