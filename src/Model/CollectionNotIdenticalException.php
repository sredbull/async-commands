<?php declare(strict_types=1);

namespace App\Model;

class CollectionNotIdenticalException extends \InvalidArgumentException
{
    public function __construct(Collection $parent, Collection $child, string $message = null)
    {
        parent::__construct(
            sprintf(
                '%s %s is not identical to %s',
                $message,
                get_class($child),
                get_class($parent)
            ),
            500
        );
    }
}
