<?php declare(strict_types=1);

namespace App\Process;

class CommandProcess extends Process
{
    public static function create(string $cwd = null, array $env = []): Process
    {
        return self::fromShellCommandline(implode(' ', $env), $cwd);
    }

    public function getCommandName(): string
    {
        $commandLine = explode(' ', $this->getCommandLine());

        return $commandLine[1];
    }

    public function getCommandConfig(): array
    {
        $commandLine = explode(' ', $this->getCommandLine());
        $configKey = array_search('--config', $commandLine, true);

        return json_decode(str_replace('\'', '', $commandLine[$configKey + 1]), true);
    }

    public function getCommandByName(string $name): ?string
    {
        foreach (get_declared_classes() as $class) {
            if (strpos($class, 'App\Command') !== false && $class::getDefaultName() === $name) {
                return $class;
            }
        }

        return null;
    }
}
