<?php declare(strict_types=1);

namespace App\Model\Process;

use App\Command\Command;
use App\Process\CommandProcess;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

trait ListenerTrait
{
    private function startProcessesListener(ProcessCollection $activeProcesses, SymfonyStyle $output): void
    {
        while (count($activeProcesses) > 0) {
            $messageCollection = new MessageCollection();

            /**
             * @var Command|string $class
             * @var CommandProcess $activeProcess
             */
            foreach ($activeProcesses as $key => $activeProcess) {
                $this->startProcesThatMetDependencies($activeProcess);

                if ($activeProcess->isStarted() === false) {
                    continue;
                }

                if ($activeProcess->getExitCode() !== null) {
                    unset($activeProcesses[$key]);
                }

                $this->fetchMessagesFromActiveProcess($messageCollection, $activeProcess);
            }

            if (count($messageCollection) > 0) {
                $messageCollection->write($output);
            }

            usleep(100);
        }
    }

    private function fetchMessagesFromActiveProcess(MessageCollection $messageCollection, Process $activeProcess): void
    {
        if ($activeProcess->getOutput() === '') {
            $activeProcess->clearOutput();

            return;
        }

        $messageCollection->merge(
            MessageCollection::fromOutput($activeProcess),
            // sort messages on datetime
            fn(Message $parent, Message $child) => $parent->getDatetime() <=> $child->getDatetime()
        );

        $activeProcess->clearOutput();
    }

    private function startProcesThatMetDependencies(CommandProcess $activeProcess): void {
        if ($activeProcess->isStarted() || $activeProcess->isRunning()) {
            return;
        }

        $canStart = true;
        /** @var CommandProcess $dependency */
        foreach ($activeProcess->getDependencies() as $dependency) {
            if ($dependency->getExitCode() === null) {
                $canStart = false;
            }
        }

        if ($canStart === true) {
            $activeProcess->start();
        }
    }
}
