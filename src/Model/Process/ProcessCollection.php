<?php

declare(strict_types=1);

namespace App\Model\Process;

use App\Process\CommandProcess;
use App\Process\Process;
use Ramsey\Collection\AbstractCollection;

class ProcessCollection extends AbstractCollection
{
    public function getType(): string
    {
        return CommandProcess::class;
    }
}
