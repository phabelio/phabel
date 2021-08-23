#!/bin/bash -e

composer install --prefer-dist --ignore-platform-reqs --no-plugins
composer bin all install --prefer-dist --ignore-platform-reqs

BRANCH=$(git rev-parse --abbrev-ref HEAD)

if [ $BRANCH != master ]; then
    VERSION=${BRANCH: -3}
fi

php tools/ci/prepareDeps.php $VERSION

sed 's/die[(]1[)];//g' -i vendor-bin/phpunit/vendor/phpunit/phpunit/phpunit

chmod +x vendor/bin/*
