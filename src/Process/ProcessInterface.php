<?php declare(strict_types=1);

namespace App\Process;

use Symfony\Component\Console\Output\OutputInterface;

interface ProcessInterface
{
    public static function create(string $cwd = null, array $env = []): self;
    public function runProcess(OutputInterface $output): int;
    public function getSource(): string;
    public function setSource(string $source): self;
}
