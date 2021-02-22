<?php declare(strict_types=1);

namespace App\Process\Apt;

use App\Process\AptProcess;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstallPackagesProcess extends AptProcess
{
    public const NAME = 'install';
    public const COMMAND = 'apt-get -y -f install $packages';

    public static function create(string $cwd = null, array $env = []): self
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('packages');
        $resolver->setAllowedTypes('packages', 'array');
        $resolver->setNormalizer('packages', fn (Options $options, $value) => implode(' ', $value));

        $env = $resolver->resolve($env);

        return self::fromShellCommandline(self::COMMAND, null, $env);
    }
}
