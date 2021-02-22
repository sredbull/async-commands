<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Process\ListenerTrait;
use App\Model\Process\ProcessCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    use ListenerTrait;

    protected static $defaultName = 'install';

    protected function configure(): void
    {
        $this
            ->setDescription('Install / update your machine.')
            ->setHelp('This command will install / update your machine to be development ready.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Process[] $processes */
        $processes = $this->getProcesses();

        $activeProcesses = new ProcessCollection();
        /** @var Command|string $class */
        foreach ($processes as $process) {
            if ($class->hasDependencies() === false) {
                $process->start();
            }
            $activeProcesses[] = $process;
        }

        $this->startProcessesListener($activeProcesses, new SymfonyStyle($input, $output));

        return Command::SUCCESS;
    }
}
