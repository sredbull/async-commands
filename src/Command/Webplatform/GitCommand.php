<?php

declare(strict_types=1);

namespace App\Command\Webplatform;

use App\Command\RequiredCwdPathTrait;

abstract class GitCommand extends WebplatformCommand
{
    use RequiredCwdPathTrait;
}
