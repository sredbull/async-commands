<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait RequiredCwdPathTrait
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['cwd']);
        $resolver->setAllowedTypes('cwd', 'string');
    }
}
