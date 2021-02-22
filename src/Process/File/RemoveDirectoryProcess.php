<?php declare(strict_types=1);

namespace App\Process\File;

use App\Process\Process;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoveDirectoryProcess extends Process
{
    public const COMMAND = 'rm -rf ./$directory';

    public static function create(string $cwd = null, array $env = []): self
    {
        if ($cwd === '/' || $cwd === null) {
            throw new \DomainException(
                'Not setting the current working directory for this process could result in potential data loss'
            );
        }

        $resolver = new OptionsResolver();
        $resolver->setRequired('directory');

        return self::fromShellCommandline(self::COMMAND, $cwd, $resolver->resolve($env));
    }
}
