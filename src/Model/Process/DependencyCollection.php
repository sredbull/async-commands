<?php

declare(strict_types=1);

namespace App\Model\Process;

use Ramsey\Collection\AbstractCollection;

class DependencyCollection extends AbstractCollection
{
    private ProcessCollection $processCollection;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->processCollection =  new ProcessCollection();
    }

    public function getType(): string
    {
        return Dependency::class;
    }

    public function getProcessCollection(): ProcessCollection
    {
        return $this->processCollection;
    }
}
