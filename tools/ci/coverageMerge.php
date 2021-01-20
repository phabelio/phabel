<?php

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Text;

require 'vendor/autoload.php';

$coverage = null;
foreach (\glob("coverage/*.php") as $file) {
    echo "Processing $file...".PHP_EOL;
    if (!$coverage) {
        $coverage = include $file;
        continue;
    }
    /** @var CodeCoverage $coverage */
    $coverage->merge(include $file);
}

if ($coverage) {
    (new Clover)->process($coverage, 'coverage/clover.xml');
    echo (new Text(50, 90, true))->process($coverage, true).PHP_EOL;
}
