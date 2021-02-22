<?php declare(strict_types=1);

namespace App\Process;

use App\Model\Traits\Property\DependenciesPropertyTrait;
use App\Model\Process\Message;
use App\Model\Process\MessageType;
use App\Model\Traits\Property\MessageTypeClosurePropertyTrait;
use App\Model\Traits\Property\SourcePropertyTrait;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process as SymfonyProcess;

abstract class Process extends SymfonyProcess implements ProcessInterface
{
    use DependenciesPropertyTrait, MessageTypeClosurePropertyTrait, SourcePropertyTrait;

    public static function create(string $cwd = null, array $env = []): self
    {
        return self::fromShellCommandline(static::COMMAND, $cwd, $env);
    }

    public static function fromShellCommandline(
        string $command,
        string $cwd = null,
        array $env = null,
        $input = null,
        ?float $timeout = 0
    ) {
        return parent::fromShellCommandline($command, $cwd, $env, $input, $timeout);
    }

    public function runProcess(OutputInterface $output): int
    {
        $return = $this->run($this->handleOutPutCallback($output));

        if ($this->getOutput() !== '' || $this->getErrorOutput() !== '') {
            return $return;
        }

        if ($this->isSuccessful() === true) {
            Message::write($output, Message::TEXT, $this->getSource(), Message::MESSAGE_OK);
        }

        if ($this->isSuccessful() === false) {
            Message::write($output, Message::ERROR, $this->getSource(), Message::MESSAGE_FAILED);
        }

        return $return;
    }

    public function handleOutPutCallback(OutputInterface $output): callable
    {
        return function (string $type, string $data) use ($output) {
            $this->write($output, $data, MessageType::determineMessageType($this, $type, $data));
        };
    }

    public function write(OutputInterface $output, string $processOutput, string $type): void
    {
        $messages = array_filter(explode(PHP_EOL, $processOutput));
        foreach ($messages as $message) {
            Message::write($output, $type, $this->getSource(), $message);
        }
    }
}
