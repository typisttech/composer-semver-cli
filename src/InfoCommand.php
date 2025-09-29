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

    const BUILD_TIMESTAMP = '@datetime@';

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
            '%-15s <info>%s</info> %s',
            $this->name,
            $this->version,
            self::BUILD_TIMESTAMP,
        );
        $io->text($app);

        $githubUrl = sprintf(
            '<href=https://github.com/typisttech/composer-semver/releases/tag/%1$s>https://github.com/typisttech/composer-semver/releases/tag/%1$s</>',
            $this->version,
        );
        // https://github.com/box-project/box/blob/b0123f358f2a32488c92e09bf56f16d185e4e3cb/src/Configuration/Configuration.php#L2116
        if (preg_match('/^(?<tag>.+)-\d+-g(?<hash>[a-f0-9]{7})$/', $this->version, $matches)) {
            // Not on a tag.
            $githubUrl = sprintf(
                '<href=https://github.com/typisttech/composer-semver/compare/%1$s...%2$s>https://github.com/typisttech/composer-semver/compare/%1$s...%2$s</>',
                $matches['tag'],
                $matches['hash'],
            );
        }
        $io->text($githubUrl);

        $io->newLine(2);
        $io->writeln('<comment>Built with:</>');

        $semVerVersion = InstalledVersions::getPrettyVersion('composer/semver');
        $semVer = sprintf(
            '%1$-15s %2$s',
            'composer/semver',
            $semVerVersion
        );
        $io->text($semVer);

        $semVerReleaseUrl = sprintf(
            '<href=https://github.com/composer/semver/releases/tag/%1$s>https://github.com/composer/semver/releases/tag/%1$s</>',
            $semVerVersion
        );
        $io->text($semVerReleaseUrl);

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

        // TODO: Add sponsor info.

        return Command::SUCCESS;
    }
}
