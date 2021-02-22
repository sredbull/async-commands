<?php

declare(strict_types=1);

namespace App\Model;

abstract class Collection implements \IteratorAggregate, \Countable
{
    protected array $values;

    public function append($item): self
    {
        $this->values[] = $item;

        return $this;
    }

    public function merge(Collection $collection, callable $callback = null): void
    {
        if (get_class($this) !== get_class($collection) ) {
            throw new CollectionNotIdenticalException(
                $this,
                $collection,
                'Appending tot the collection failed'
            );
        }

        $this->values = array_merge($this->values, $collection->values);

        if ($callback !== null) {
            usort($this->values, $callback);
        }
    }

    public function count(): int
    {
        return count($this->values);
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }
}
