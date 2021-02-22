<?php declare(strict_types=1);

namespace App\Model\Traits\Property;

trait MessageTypeClosurePropertyTrait
{
    private ?\Closure $messageTypeClosure = null;

    public function getMessageTypeClosure(): ?\Closure
    {
        return $this->messageTypeClosure;
    }

    public function setMessageTypeClosure(?\Closure $messageTypeClosure): self
    {
        $this->messageTypeClosure = $messageTypeClosure;

        return $this;
    }
}
