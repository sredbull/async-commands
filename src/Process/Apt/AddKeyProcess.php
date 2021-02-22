<?php declare(strict_types=1);

namespace App\Process\Apt;

use App\Process\AptProcess;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddKeyProcess extends AptProcess
{
    public const NAME = 'key';
    public const COMMAND = 'curl -fsSL $url | APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1 apt-key add -';

    public static function create(string $cwd = null, array $env = []): self
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('url');

        return self::fromShellCommandline(self::COMMAND, null, $resolver->resolve($env));
    }
}
