<?php declare(strict_types=1);

namespace App\Model\Traits\Property;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait ConfigPropertyTrait
{
    private array $config = [];

    abstract protected function configureOptions(OptionsResolver $resolver): void;

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): self
    {
        $resolver = new OptionsResolver();

        $this->configureOptions($resolver);
        $this->config = $resolver->resolve($config);

        return $this;
    }

    public function getConfigParameter(string $parameter)
    {
        return $this->config[$parameter] ?? null;
    }
}
