#!/bin/bash -ex

phabel=$PWD
php tools/convertPhabel.php "$1" dry

cd ../phabelConvertedOutput

export BRANCH=master-$1

php$1 tools/ci/prepareDeps.php
php$1 vendor/bin/phpunit