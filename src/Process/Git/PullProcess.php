<?php declare(strict_types=1);

namespace App\Process\Git;

use App\Model\Process\Message;
use App\Process\Process;

class PullProcess extends Process
{
    public const COMMAND = 'sudo -u $SUDO_USER git pull';

    public static function create(string $cwd = null, array $env = []): Process
    {
        $process = self::fromShellCommandline(self::COMMAND, $cwd, $env);

        $process->setMessageTypeClosure(\Closure::fromCallable(function ($data) use ($env) {
            return Message::TEXT;
        }));

        return $process;
    }
}
