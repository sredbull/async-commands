<?php declare(strict_types=1);

namespace App\Model\Traits\Property;

trait DependenciesPropertyTrait
{
    protected array $dependencies = [];

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies): self
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    public function addDependency($dependency): self
    {
        $this->dependencies[] = $dependency;

        return $this;
    }

    public function hasDependencies(): bool
    {
        return count($this->dependencies) > 0;
    }
}
