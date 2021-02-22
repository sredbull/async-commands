<?php

declare(strict_types=1);

namespace App\Model\Process;

use Datetime;
use Symfony\Component\Console\Output\OutputInterface;

class Message
{
    public const TEXT = 'text';
    public const NOTE = 'note';
    public const COMMENT = 'comment';
    public const ERROR = 'error';
    public const MESSAGE_OK = 'OK';
    public const MESSAGE_FAILED = 'FAILED';

    private Datetime $datetime;
    private string $type;
    private string $source;
    private string $message;

    public function __construct(Datetime $datetime, string $type, string $source, string $message)
    {
        $this->datetime = $datetime;
        $this->type = $type;
        $this->source = $source;
        $this->message = $message;
    }

    public function getDatetime(): Datetime
    {
        return $this->datetime;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public static function write(OutputInterface $output, string $type, string $source, string $message): void
    {
        $output->writeln(
            implode(',', [(new DateTime())->format('Y-m-d H:i:s'), $type, $source, $message])
        );
    }
}
