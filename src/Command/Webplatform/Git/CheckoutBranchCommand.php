<?php

declare(strict_types=1);

namespace App\Command\Webplatform\Git;

use App\Command\Command;
use App\Command\Webplatform\GitCommand;
use App\Process\Git\CheckoutBranchProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckoutBranchCommand extends GitCommand
{
    protected static $defaultName = 'webplatform:git:checkout-branch';
    protected array $dependencies = [ResetCommand::class];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = CheckoutBranchProcess::create($this->getConfigParameter('cwd'));
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));

        $process->runProcess($output);

        return Command::SUCCESS;
    }
}
