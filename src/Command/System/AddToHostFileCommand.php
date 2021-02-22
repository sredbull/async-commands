<?php

declare(strict_types=1);

namespace App\Command\System;

use App\Command\Command;
use App\Model\Process\Message;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use tyurderi\Hosts\Editor;

class AddToHostFileCommand extends Command
{
    protected static $defaultName = 'add:to-host-file';

    protected function configure(): void
    {
        $this
            ->setDescription('Update the /etc/host file.')
            ->setHelp('This command will update your host file.')
        ;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['hosts']);
        $resolver->setAllowedTypes('hosts', 'string[]');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $editor = new Editor();

        if ($editor->getFile()->isReadable() === false) {
            Message::write($output, Message::ERROR, self::$defaultName, '/etc/hosts is not readable');

            return Command::FAILURE;
        }

        if ($editor->getFile()->isWritable() === false) {
            Message::write($output, Message::ERROR, self::$defaultName, '/etc/hosts is not writeable');

            return Command::FAILURE;
        }

        $editor->parse();

        $hostCount = 0;
        foreach ($this->getConfigParameter('hosts') as $host) {
            if ($editor->find($host) === null) {
                $editor->push('127.0.0.1', $host);

                Message::write(
                    $output,
                    Message::TEXT,
                    self::$defaultName,
                    sprintf('Host %s added to /etc/hosts', $host)
                );

                ++$hostCount;
            }
        }

        if  ($hostCount === 0) {
            Message::write($output, Message::NOTE, self::$defaultName, 'No hosts found to add');

            return Command::SUCCESS;
        }

        if ($editor->getFile()->isWritable() === true) {
            $editor->write();

            Message::write(
                $output,
                Message::NOTE,
                self::$defaultName,
                sprintf('%s total hosts added to /etc/hosts', $hostCount)
            );

            return Command::SUCCESS;
        }

        Message::write(
            $output,
            Message::NOTE,
            self::$defaultName,
            'Unknown error encountered writing to /etc/hosts'
        );

        return Command::FAILURE;
    }
}
