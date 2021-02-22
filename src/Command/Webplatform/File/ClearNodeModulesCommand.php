<?php

declare(strict_types=1);

namespace App\Command\Webplatform\File;

use App\Command\Webplatform\FileCommand;
use App\Command\Webplatform\Git\StashCommand;

class ClearNodeModulesCommand extends FileCommand
{
    public const DIRECTORY = 'node_modules';

    protected static $defaultName = 'webplatform:file:clear-node_modules';
    protected array $dependencies = [StashCommand::class];
    protected static array $tags = ['no-clear'];
}
