<?php

declare(strict_types=1);

namespace App\Model\Process;

use App\Model\Collection;
use Datetime;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class MessageCollection extends Collection
{
    /**
     * @var Message[]|array
     */
    protected array $values;

    public function __construct(Message ...$messages)
    {
        $this->values = $messages;
    }

    /**
     * @return Message[]|MessageCollection
     */
    public static function fromOutput(Process $process): MessageCollection
    {
        try {
            return new self(
                ...array_map(
                    static function (string $message) {
                        $message = explode(',', $message);
                        $message[0] = new DateTime($message[0]);

                        return new Message(...$message);
                    },
                    array_filter(explode(PHP_EOL, $process->getOutput()))
                )
            );
        } catch (\Throwable $exception) {
            return new self(
                new Message(
                    new DateTime(),
                    Message::ERROR,
                    explode(' ', $process->getCommandLine())[1],
                    sprintf(
                        'process output has an unfamiliar format, consider running this command manually: %s',
                        $process->getCommandLine()
                    )
                )
            );
        }
    }

    public function write(SymfonyStyle $output): void
    {
        /** @var Message $message */
        foreach ($this as $message) {
            $output->{$message->getType()}(
                sprintf(
                    '%s - %s - %s',
                    $message->getDatetime()->format('Y-m-d H:i:s'),
                    $message->getSource(),
                    $message->getMessage())
            );
        }
    }
}
