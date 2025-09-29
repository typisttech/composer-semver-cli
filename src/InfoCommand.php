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
    private const string GIT_COMMIT = '@git-commit@';

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

        $release = sprintf(
            '<href=https://github.com/typisttech/composer-semver/releases/tag/%1$s>https://github.com/typisttech/composer-semver/releases/tag/%1$s</>',
            $this->version,
        );
        // https://github.com/box-project/box/blob/b0123f358f2a32488c92e09bf56f16d185e4e3cb/src/Configuration/Configuration.php#L2116
        if (preg_match('/^.+-\d+-g[a-f0-9]{7,}$/', $this->version)) {
            // Not on a tag.
            $release = sprintf(
                '<href=https://github.com/typisttech/composer-semver/commit/%1$s>https://github.com/typisttech/composer-semver/commit/%1$s</>',
                self::GIT_COMMIT,
            );
        }
        $io->text($release);

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

        // TODO: Add sponsor info.

        return Command::SUCCESS;
    }
}
