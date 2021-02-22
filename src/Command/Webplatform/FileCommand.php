<?php

declare(strict_types=1);

namespace App\Command\Webplatform;

use App\Command\Command;
use App\Command\RequiredCwdPathTrait;
use App\Process\File\RemoveDirectoryProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class FileCommand extends WebplatformCommand
{
    use RequiredCwdPathTrait;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = RemoveDirectoryProcess::create(
            $this->getConfigParameter('cwd'),
            ['directory' => static::DIRECTORY]
        );
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));

        $process->runProcess($output);

        return Command::SUCCESS;
    }
}
