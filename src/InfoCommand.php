<?php

declare(strict_types=1);

namespace TypistTech\ComposerSemVer;

use Composer\InstalledVersions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'info',
    description: 'Prints various details about the Composer SemVer environment.',
)]
class InfoCommand extends Command
{
    private const BANNER = <<<BANNER
                        ____
                       / ___|___  _ __ ___  _ __   ___  ___  ___ _ __
                      | |   / _ \| '_ ` _ \| '_ \ / _ \/ __|/ _ \ '__|
                      | |__| (_) | | | | | | |_) | (_) \__ \  __/ |
                       \____\___/|_| |_| |_| .__/_\___/|___/\___|_|
                      / ___|  ___ _ __ __\ \_| / /__ _ __
                      \___ \ / _ \ '_ ` _ \ \ / / _ \ '__|
                       ___) |  __/ | | | | \ V /  __/ |
                      |____/ \___|_| |_| |_|\_/ \___|_|
                      BANNER;

    public function __construct(
        private readonly string $name,
        private readonly string $version,
    ) {
        parent::__construct();
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $bannerRows = explode("\n", self::BANNER);

        $io->text($bannerRows);
        $io->newLine(2);

        $app = sprintf(
            '%-15s <info>%s</info>',
            $this->name,
            $this->version,
        );
        $io->text($app);

        $io->newLine(2);
        $io->writeln('<comment>Built with:</>');
        $semVer = sprintf(
            '%1$-15s <href=https://github.com/composer/semver/releases/tag/%2$s>%2$s</>',
            'composer/semver',
            InstalledVersions::getPrettyVersion('composer/semver'),
        );
        $io->text($semVer);

        $io->newLine();
        $io->writeln('<comment>PHP:</>');

        $phpVersion = sprintf(
            '%-15s %s',
            'Version',
            PHP_VERSION,
        );
        $io->text($phpVersion);

        // TODO: Print PHP_BUILD_DATE for PHP >=8.5.

        $phpVersion = sprintf(
            '%-15s %s',
            'SAPI',
            PHP_SAPI,
        );
        $io->text($phpVersion);

        $phpBinPath = match (true) {
            ! empty(PHP_BINARY) => PHP_BINARY,
            PHP_SAPI === 'micro' => 'N/A',
            default => 'Unknown',
        };
        $phpBin = sprintf(
            '%-15s %s',
            'Binary',
            $phpBinPath,
        );
        $io->text($phpBin);

        $io->newLine();
        $io->writeln('<comment>OS:</>');

        $systemOs = sprintf(
            '%s %s %s %s',
            php_uname('s'),
            php_uname('r'),
            php_uname('v'),
            php_uname('m'),
        );
        $io->text($systemOs);

        // TODO: Add sponsor info.

        return Command::SUCCESS;
    }
}
