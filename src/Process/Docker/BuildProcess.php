<?php declare(strict_types=1);

namespace App\Process\Docker;

use App\Process\Process;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildProcess extends Process
{
    public const COMMAND = 'sudo -u $SUDO_USER dcweb build --pull $service';

    public static function create(string $cwd = null, array $env = []): Process
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('service');

        return self::fromShellCommandline(self::COMMAND, $cwd, $resolver->resolve($env));
    }
}
