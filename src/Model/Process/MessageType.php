<?php declare(strict_types=1);

namespace App\Model\Process;

use App\Process\Process;

class MessageType
{
    public static function determineMessageType(Process $process, string $type, string $data): string
    {
        $messageType = Message::TEXT;
        if ($type === Process::ERR) {
            $messageType = Message::ERROR;
        }

        if ($process->getMessageTypeClosure() !== null) {
            $method = $process->getMessageTypeClosure();

            $messageType = $method($data) ?? $messageType;
        }

        return $messageType;
    }
}
