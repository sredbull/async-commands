<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\OptionsResolver\Options;

trait DefaultsFromOptionsTrait
{
    public static function getDefaultsFromOptions(Options $options): array
    {
        $reflector = new \ReflectionObject($options);
        $property = $reflector->getProperty('defaults');
        $property->setAccessible(true);

        return $property->getValue($options);
    }
}
