<?php

namespace Phabel;

/**
 * Various version headers.
 */
class Version
{
    /**
     * Latest git tag.
     */
    public const VERSION = '1.0.0';
    /**
     * Latest revision.
     */
    public const LATEST = 0;
    /**
     * Changelog.
     */
    public const CHANGELOG = [
        0 => '<bold>Welcome! You can now use PHP 8 features in your code.</bold>

<bold>Config recap:</bold>
<bold>✅</bold> Source PHP version: <phabel>8.0</phabel>
<bold>✅</bold> Target PHP version: <phabel>auto</phabel> <bold>(7.0|7.1|7.2|7.3|7.4|8.0)</bold>
<bold>✅</bold> Dependency transpilation: <phabel>Enabled</phabel>

<phabel>To publish your composer package using phabel, run this command after git tagging a release:</phabel>
<bold>vendor/bin/phabel tag</bold>

<phabel>See https://phabel.io for documentation and more configuration parameters.</phabel>
<bold>Have fun!</bold>
',
    ];
}
