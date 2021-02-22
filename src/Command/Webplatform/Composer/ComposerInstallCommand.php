<?php

declare(strict_types=1);

namespace App\Command\Webplatform\Composer;

use App\Command\Command;
use App\Command\Webplatform\ComposerCommand;
use App\Command\Webplatform\Git\PullCommand;
use App\Process\Composer\ComposerInstallProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ComposerInstallCommand extends ComposerCommand
{
    protected static $defaultName = 'webplatform:composer:install';
    protected array $dependencies = [PullCommand::class];
    protected static array $tags = ['no-composer'];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = ComposerInstallProcess::create($this->getConfigParameter('cwd'));
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));

        $process->runProcess($output);

        return Command::SUCCESS;
    }
}
