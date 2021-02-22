<?php declare(strict_types=1);

namespace App\Process;

use App\Command\AptCommand;

abstract class AptProcess extends Process
{
    public function getSource(): string
    {
        return $this->source ?? sprintf('%s[%s]', AptCommand::getDefaultName(), static::NAME);
    }

    public function setSource(string $source): self
    {
        $this->source = sprintf('%s[%s][%s]', AptCommand::getDefaultName(), static::NAME, $source);

        return $this;
    }
}
