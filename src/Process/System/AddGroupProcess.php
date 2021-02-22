<?php declare(strict_types=1);

namespace App\Process\System;

use App\Process\Process;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddGroupProcess extends Process
{
    public const COMMAND = 'groupadd -r $group';

    public static function create(string $cwd = null, array $env = []): self
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('group');

        return self::fromShellCommandline(self::COMMAND, $cwd, $resolver->resolve($env));
    }
}
