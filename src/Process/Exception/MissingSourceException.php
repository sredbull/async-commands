<?php declare(strict_types=1);

namespace App\Process\Exception;

use App\Process\Process;

class MissingSourceException extends \DomainException
{
    public function __construct(Process $process)
    {
        parent::__construct(sprintf('The source for %s is not set.', get_class($process)), 500);
    }
}
