<?php declare(strict_types=1);

namespace App\Process\Apt;

use App\Process\AptProcess;

class RemovePackagesProcess extends AptProcess
{
    public const NAME = 'remove';
    public const COMMAND = 'apt-get -y -f remove $packages';

    public static function create(string $cwd = null, array $env = []): self
    {
        return self::fromShellCommandline(self::COMMAND, null, $env);
    }
}
