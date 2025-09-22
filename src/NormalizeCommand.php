<?php

declare(strict_types=1);

namespace TypistTech\ComposerSemVer;

use Composer\Semver\VersionParser;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'normalize',
    description: 'Normalizes a version string to be able to perform comparisons on it.',
)]
class NormalizeCommand extends Command
{
    public function __construct(
        public readonly VersionParser $parser = new VersionParser(),
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument]
        string $version,
        #[Option(description: 'Complete version string to give more context.')]
        ?string $fullVersion = null,
    ): int {
        try {
            $normalized = $this->parser->normalize($version, $fullVersion);
            $io->writeln($normalized);

            return Command::SUCCESS;
        } catch (\UnexpectedValueException $e) {
            $io
                ->getErrorStyle()
                ->error(
                    $e->getMessage(),
                );

            return Command::FAILURE;
        }
    }
}
