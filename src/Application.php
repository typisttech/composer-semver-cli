<?php

declare(strict_types=1);

namespace TypistTech\ComposerSemVer;

use Composer\Semver\VersionParser;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

class Application
{
    public static function make(): SymfonyConsoleApplication
    {
        $app = new SymfonyConsoleApplication('Composer SemVer', '0.1.0');

        $parser = new VersionParser;

        $app->addCommands([
            new NormalizeCommand($parser),
            new ParseCommand($parser),
        ]);

        return $app;
    }
}
