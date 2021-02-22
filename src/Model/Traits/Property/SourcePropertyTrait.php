<?php declare(strict_types=1);

namespace App\Model\Traits\Property;

use App\Process\Exception\MissingSourceException;

trait SourcePropertyTrait
{
    protected ?string $source = null;

    public function getSource(): string
    {
        if ($this->source === null) {
            throw new MissingSourceException($this);
        }

        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
