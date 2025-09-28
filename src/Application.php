<?php

declare(strict_types=1);

namespace TypistTech\ComposerSemVer;

use Composer\Semver\VersionParser;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

class Application
{
    public static function run(): int
    {
        $name = 'Composer SemVer CLI';
        $version = '0.1.0-dev';

        $app = new SymfonyConsoleApplication($name, $version);

        $parser = new VersionParser();

        $app->addCommands([
            new NormalizeCommand($parser),
            new ParseCommand($parser),
            new InfoCommand($name, $version),
        ]);

        return $app->run();
    }
}
