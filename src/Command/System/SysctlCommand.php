<?php

declare(strict_types=1);

namespace App\Command\System;

use App\Command\Command;
use App\Process\System\ConfigureSysctlProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SysctlCommand extends Command
{
    protected static $defaultName = 'configure:sysctl';

    protected function configure(): void
    {
        $this
            ->setDescription('Configure the system kernel with sysctl.')
            ->setHelp('This command will configure the system kernel with sysctl by config file and the command.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['configFile', 'attributes']);
        $resolver->setAllowedTypes('configFile', 'file');
        $resolver->setAllowedTypes('attributes', 'array[]');
        $resolver->setDefault('attributes', function (OptionsResolver $attributesResolver, Options $parent) {
            $defaults = self::getDefaultsFromOptions($parent);
            $attributeKeys = array_keys($defaults['attributes']);
            $attributesResolver->setRequired(...$attributeKeys);

            foreach ($attributeKeys as $key) {
                $attributesResolver->setDefault($key, function (OptionsResolver $attributeResolver) {
                    $attributeResolver->setRequired(['attribute', 'value']);
                    $attributeResolver->setAllowedTypes('attribute', 'string');
                    $attributeResolver->setAllowedTypes('value', 'string');
                });
            }
        });
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getConfigParameter('attributes') as $attribute) {
            $process = ConfigureSysctlProcess::create($attribute);
            $process->setSource(static::getDefaultName());
            $process->runProcess($output);

            $config = $attribute;
            $config['configFile'] = $this->getConfigParameter('configFile');

            /** @var EditConfigurationFileCommand $command */
            $command = $this->getApplication()->find(EditConfigurationFileCommand::getDefaultName());
            $command->setConfig($config);
            $command->run($input, $output);
        }

        return Command::SUCCESS;
    }
}
