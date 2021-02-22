<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Finder\Finder;

trait ProjectPathsTrait
{
    private function getProjectPaths(string $path, array $excludedPaths): array
    {
        $finder = new Finder();
        $finder
            ->files()
            ->ignoreDotFiles(false)
            ->ignoreVCS(false)
            ->ignoreUnreadableDirs()
            ->in($path)
            ->path('.git')
            ->name('ORIG_HEAD')
            ->exclude($excludedPaths);

        $projectPaths = [];
        foreach ($finder as $project) {
            $projectPaths['paths'][] = str_replace('/.git', '', $project->getPath());
        }

        return $projectPaths;
    }
}
