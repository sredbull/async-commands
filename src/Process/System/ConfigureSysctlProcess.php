<?php declare(strict_types=1);

namespace App\Process\System;

use App\Process\Process;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigureSysctlProcess extends Process
{
    public const COMMAND = 'sysctl -w $attribute=$value';

    public static function create(string $cwd = null, array $env = []): self
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['attribute', 'value']);

        return self::fromShellCommandline(self::COMMAND, $cwd, $resolver->resolve($env));
    }
}
