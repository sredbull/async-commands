<?php declare(strict_types=1);

namespace App\Process\Composer;

use App\Model\Process\Message;
use App\Process\Process;

class ComposerInstallProcess extends Process
{
    public const COMMAND = 'sudo -u $SUDO_USER composer install --ignore-platform-reqs';

    public static function create(string $cwd = null, array $env = []): Process
    {
        $process = self::fromShellCommandline(self::COMMAND, $cwd, $env);

        $process->setMessageTypeClosure(\Closure::fromCallable(function ($data) use ($env) {
            return Message::TEXT;
        }));

        return $process;
    }
}
