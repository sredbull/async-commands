<?php

declare(strict_types=1);

namespace App\Command\System;

use App\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditConfigurationFileCommand extends Command
{
    protected static $defaultName = 'edit:configuration-file';

    protected function configure(): void
    {
        $this
            ->setDescription('Append to or edit a configuration file.')
            ->setHelp('This command will append er edit a configuration file.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['attribute', 'configFile', 'value']);
        $resolver->setAllowedTypes('attribute', 'string');
        $resolver->setAllowedTypes('value', 'string');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->getConfig();

        $file = new \SplFileInfo($config['configFile']);
//        if ($file->isFile() === false) {
//            Throw new IsNotAfileException($config['configFile']);
//        }

//        dd($file);
//
//        dd(explode(PHP_EOL, (new SmartFileSystem())->readFile($config['configFile'])));
//


        return Command::SUCCESS;
    }
}
