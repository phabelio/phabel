<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\RootNode;

/**
 * Removes the file blocking inclusion of non-transpiled packages.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class ComposerSanitizer extends Plugin
{
    public const FILE_NAME = '___transpiledWithPhabel.php';
    private const MESSAGE = <<<PHP
    This package requires transpilation using the phabel.io composer plugin.
    To use this package please run composer update, enabling execution of plugins and generation of the lockfile.
    
    PHP;

    /**
     * Get contents of file.
     *
     * @param string $package
     * @return string
     */
    public static function getContents(string $package): string
    {
        return \sprintf('<?php die("%s: %s");', $package, self::MESSAGE);
    }
    public function shouldRunFile(string $file): bool
    {
        return \basename($file) === self::FILE_NAME;
    }

    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $root, Context $context): void
    {
        $root->stmts = [];
    }
}
