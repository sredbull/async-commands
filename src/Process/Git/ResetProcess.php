<?php declare(strict_types=1);

namespace App\Process\Git;

use App\Process\Process;

class ResetProcess extends Process
{
    public const COMMAND = 'git reset --hard';
}
