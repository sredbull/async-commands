<?php

declare(strict_types=1);

namespace App;

use App\Command\Command as AppCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends SymfonyApplication
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container, string $name, string $version)
    {
        parent::__construct($name, $version);

        $this->container = $container;
    }

    public function addIterableCommands(iterable $commands): void
    {
        foreach ($commands as $command) {
            $this->setConfigForCommand($command);
            $this->add($command);
        }
    }

    /**
     * @param Command|AppCommand $command
     */
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output): int
    {
        if ($command instanceof AppCommand === false) {
            return parent::doRunCommand($command, $input, $output);
        }

        $command->addOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'A json encoded string to configure the command',
            '{}'
        );

        $command->mergeApplicationDefinition();
        $input->bind($command->getDefinition());

        $command->setConfig(
            array_merge(
                $command->getConfig() ?? [],
                json_decode($input->getOption('config'), true) ?? []
            )
        );

        return parent::doRunCommand($command, $input, $output);
    }

    /**
     * @param Command|AppCommand $command
     */
    private function setConfigForCommand(Command $command): void
    {
        if ($this->container->hasParameter($command::getDefaultName()) === false) {
            return;
        }

        $command->setConfig($this->container->getParameter($command::getDefaultName()));
    }
}
