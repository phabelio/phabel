<?php

require 'tools/ci/functions.php';

$versions = require 'tools/ci/versions.php';

if ($argc < 2) {
    die("Need a tag name!".PHP_EOL);
}

$tag = escapeshellarg($argv[1]);
r("git pull");
r("git checkout master");
foreach ($versions as $version) {
    r("git branch -D master-$version || true");
    r("git checkout master-$version");
    r("git tag $tag.$version");
    r("git push origin $tag.$version");
}