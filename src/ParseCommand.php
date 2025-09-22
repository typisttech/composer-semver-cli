<?php

declare(strict_types=1);

namespace TypistTech\ComposerSemVer;

use Composer\Semver\VersionParser;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'parse',
    description: 'Parses a constraint string and strip its ignorable parts.',
)]
class ParseCommand extends Command
{
    public function __construct(
        public readonly VersionParser $parser = new VersionParser(),
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument]
        string $constraints,
    ): int {
        try {
            $parsed = $this->parser->parseConstraints($constraints);
            $io->writeln(
                (string) $parsed,
            );

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
