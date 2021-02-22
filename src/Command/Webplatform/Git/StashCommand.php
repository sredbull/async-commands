<?php

declare(strict_types=1);

namespace App\Command\Webplatform\Git;

use App\Command\Command;
use App\Command\Webplatform\GitCommand;
use App\Process\Git\StashProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StashCommand extends GitCommand
{
    protected static $defaultName = 'webplatform:git:stash';
    protected array $dependencies = [AddCommand::class];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = StashProcess::create($this->getConfigParameter('cwd'));
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));

        $process->runProcess($output);

        return Command::SUCCESS;
    }
}
