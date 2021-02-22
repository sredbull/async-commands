<?php declare(strict_types=1);

namespace App\Process\Apt;

use App\Process\AptProcess;

class UpdateProcess extends AptProcess
{
    public const NAME = 'update';
    public const COMMAND = 'apt-get update';

    public static function create(string $cwd = null, array $env = []): self
    {
        return self::fromShellCommandline(self::COMMAND);
    }
}
