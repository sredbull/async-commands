<?php declare(strict_types=1);

namespace App\Process\Apt;

use App\Process\AptProcess;

class AutoremoveProcess extends AptProcess
{
    public const NAME = 'autoremove';
    public const COMMAND = 'apt-get -y autoremove';

    public static function create(string $cwd = null, array $env = []): self
    {
        return self::fromShellCommandline(self::COMMAND);
    }
}
