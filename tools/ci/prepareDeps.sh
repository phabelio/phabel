#!/bin/bash -e

composer install --prefer-dist --ignore-platform-reqs --no-plugins

git stash

BRANCH=$(git rev-parse --abbrev-ref HEAD)

if [ $BRANCH != master ]; then
    VERSION=${BRANCH: -3}
    git checkout ${BRANCH:0:-3}
fi

php tools/ci/prepareDeps.php $VERSION

if [ $BRANCH != master ]; then
    git checkout $BRANCH
fi

git stash pop || exit 0