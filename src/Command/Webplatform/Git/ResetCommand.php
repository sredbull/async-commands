<?php

declare(strict_types=1);

namespace App\Command\Webplatform\Git;

use App\Command\Command;
use App\Command\Webplatform\File\ClearNodeModulesCommand;
use App\Command\Webplatform\File\ClearVarCommand;
use App\Command\Webplatform\File\ClearVendorCommand;
use App\Command\Webplatform\GitCommand;
use App\Process\Git\ResetProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends GitCommand
{
    protected static $defaultName = 'webplatform:git:reset';
    protected array $dependencies = [ClearNodeModulesCommand::class, ClearVarCommand::class, ClearVendorCommand::class];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = ResetProcess::create($this->getConfigParameter('cwd'));
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));

        $process->runProcess($output);

        return Command::SUCCESS;
    }
}
