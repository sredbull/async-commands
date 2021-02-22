<?php

declare(strict_types=1);

namespace App\Command\Webplatform\Docker;

use App\Command\Command;
use App\Command\Webplatform\DockerCommand;
use App\Model\Process\Message;
use App\Process\Docker\BuildProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class  BuildCommand extends DockerCommand
{
    protected static $defaultName = 'webplatform:docker:build';
    protected static array $tags = ['no-build'];

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['docker-compose-file']);
        $resolver->setAllowedTypes('docker-compose-file', 'file');
        $resolver->setDefault('cwd', '.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cwd = $this->getConfigParameter('cwd');

        $path = explode('/', $cwd);
        $path = implode('/', array_slice($path, 0, -1));

        $services = $this->getServiceFromDockerComposeFile($cwd, $path );
        if ($services === null) {
            Message::write(
                $output,
                Message::TEXT,
                static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'),
                'no services to build'
            );

            return Command::SUCCESS;
        }

        foreach ($services as $service) {
            $this->runBuildProcess($service, $output);
        }

        return Command::SUCCESS;
    }

    private function getServiceFromDockerComposeFile(string $cwd, string $path): ?array
    {
        $projects = null;
        $dockerComposeConfig = Yaml::parseFile($this->getConfigParameter('docker-compose-file'));
        foreach ($dockerComposeConfig['services'] as $key => $service) {
            if (array_key_exists('build', $service) === false
                || array_key_exists('context', $service['build']) === false
            ) {
                continue;
            }

            $projectPath = str_replace('${PLATFORM_PATH}', $path . '/', $service['build']['context']);
            if ($cwd !== $projectPath) {
                continue;
            }

            $projects[] = $key;
        }

        return $projects;
    }

    protected function runBuildProcess(string $service, OutputInterface $output): void
    {
        $process = BuildProcess::create($this->getConfigParameter('cwd'), ['service' => $service]);
        $process->setSource(static::getDefaultName() . ' ' . $this->getConfigParameter('cwd'));
        $process->runProcess($output);
    }
}
