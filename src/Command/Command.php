<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Traits\Property\ConfigPropertyTrait;
use App\Process\CommandProcess;
use App\Model\Traits\Property\DependenciesPropertyTrait;
use App\Process\Process;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    use DefaultsFromOptionsTrait, ConfigPropertyTrait, DependenciesPropertyTrait;

    public const SUCCESS = 0;
    public const FAILURE = 1;

    protected static ?string $commandFilter = null;
    protected static array $tags = [];

    protected function getCommands(?string $filter = null, array $excludedTags = []): array
    {
        $commands = [];

        foreach ($this->getApplication()->all() as $name => $command) {
            if (
                $command instanceof self === false
                || $filter !== $command::$commandFilter
                || self::getDefaultName() === $command->getName()
                || count(array_diff($excludedTags, $command::$tags)) !== count($excludedTags)
            ) {
                continue;
            }

            $commands[get_class($command)] = $command;
        }

        return $commands;
    }

    protected function getProcesses(?string $filter = null): array
    {
        $processes = [];

        foreach ($this->getApplication()->all() as $name => $command) {
            if (
                $command instanceof self === false
                || $filter !== $command::$commandFilter
                || self::getDefaultName() === $command->getName()
            ) {
                continue;
            }

            $processes[get_class($command)] = $command::createProcess();
        }

        return $processes;
    }

    protected static function createProcess(array $config = []): Process
    {
        $process = CommandProcess::create(
            sprintf('%s/..', dirname(__DIR__)),
            [
                'bin/console',
                static::getDefaultName(),
                '--config',
                sprintf('\'%s\'', json_encode($config)),
            ]
        );
        $process->setTimeout(0);

        return $process;
    }
}
