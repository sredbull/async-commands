<?php

declare(strict_types=1);

namespace App\Command\Webplatform;

use App\Command\Command;

abstract class WebplatformCommand extends Command
{
    protected static ?string $commandFilter = 'webplatform';
}
