<?php

declare(strict_types=1);

namespace App\Command\Webplatform;

use App\Command\RequiredCwdPathTrait;

abstract class DockerCommand extends WebplatformCommand
{
    use RequiredCwdPathTrait;
}
