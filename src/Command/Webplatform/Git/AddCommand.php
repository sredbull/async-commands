<?php

declare(strict_types=1);

namespace App\Command\Webplatform\Git;

use App\Command\Command;
use App\Command\Webplatform\GitCommand;
use App\Process\Git\AddProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class  AddCommand extends GitCommand
{
    protected static $defaultName = 'webplatform:git:add';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = AddProcess::create($this->getConfigParameter('cwd'));
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));

        $process->runProcess($output);

        return Command::SUCCESS;
    }
}
