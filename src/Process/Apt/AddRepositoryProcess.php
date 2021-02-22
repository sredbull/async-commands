<?php declare(strict_types=1);

namespace App\Process\Apt;

use App\Model\Process\Message;
use App\Process\AptProcess;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRepositoryProcess extends AptProcess
{
    public const NAME = 'repo';
    public const COMMAND = 'ubuntuCodename=$(. /etc/os-release; echo "$UBUNTU_CODENAME") && add-apt-repository "%s"';

    public static function create(string $cwd = null, array $env = []): self
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('command');

        $env = $resolver->resolve($env);

        $process = self::fromShellCommandline(sprintf(self::COMMAND, $env['command']));

        $process->setMessageTypeClosure(\Closure::fromCallable(function ($data) {
            if (strpos($data, 'already exists') !== false) {
                return Message::NOTE;
            }
        }));

        return $process;
    }
}
