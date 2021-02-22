<?php declare(strict_types=1);

namespace App\Model\Process;

use App\Process\Process;

class Dependency
{
    private Process $process;
    private array $dependencies;

    public function __construct(Process $process, array $dependencies)
    {
        $this->process = $process;
        $this->dependencies = $dependencies;
    }

    public function getProcess(): Process
    {
        return $this->process;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
