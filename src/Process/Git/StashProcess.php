<?php declare(strict_types=1);

namespace App\Process\Git;

use App\Process\Process;

class StashProcess extends Process
{
    public const COMMAND = 'git stash';
}
