<?php

declare(strict_types=1);

namespace TypistTech\ComposerSemVer;

use Composer\Semver\VersionParser;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

class Application
{
    private const string NAME = 'Composer SemVer';
    private const string GIT_TAG = '@git-tag@';

    public static function run(): int
    {
        $app = new SymfonyConsoleApplication(self::NAME, self::GIT_TAG);

        $parser = new VersionParser();

        $app->addCommands([
            new NormalizeCommand($parser),
            new ParseCommand($parser),
            new InfoCommand(self::NAME, self::GIT_TAG),
        ]);

        return $app->run();
    }
}
