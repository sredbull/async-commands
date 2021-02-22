<?php declare(strict_types=1);

namespace App\Process\Git;

use App\Model\Process\Message;
use App\Process\Process;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutBranchProcess extends Process
{
    public const DEFAULT_BRANCH = 'master';
    public const COMMAND = 'git checkout $branch';

    public static function create(string $cwd = null, array $env = []): self
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['branch']);
        $resolver->setDefault('branch', self::DEFAULT_BRANCH);

        $env = $resolver->resolve($env);

        $process = self::fromShellCommandline(self::COMMAND, $cwd, $env);

        $process->setMessageTypeClosure(\Closure::fromCallable(function ($data) use ($env) {
            if (strpos($data, 'Already on \'' . $env['branch'] . '\'') !== false) {
                return Message::TEXT;
            }
        }));

        return $process;
    }
}
