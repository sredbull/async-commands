<?php

declare(strict_types=1);

namespace App\Command\Webplatform\File;

use App\Command\Webplatform\FileCommand;
use App\Command\Webplatform\Git\StashCommand;

class ClearVendorCommand extends FileCommand
{
    public const DIRECTORY = 'vendor';

    protected static $defaultName = 'webplatform:file:clear-vendor';
    protected array $dependencies = [StashCommand::class];
    protected static array $tags = ['no-clear'];
}
