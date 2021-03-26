#!/bin/bash -e

composer install --prefer-dist --ignore-platform-reqs --no-plugins

git stash

BRANCH=$(git rev-parse --abbrev-ref HEAD)
VERSION=${BRANCH: -3}
git checkout ${BRANCH:0:-3}

php tools/ci/prepareDeps.php $VERSION

git checkout $BRANCH
git stash pop