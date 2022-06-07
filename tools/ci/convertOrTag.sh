#!/bin/bash -e

export PHP_CS_FIXER_IGNORE_ENV=1
export PHABEL_PARALLEL=1

if [ "$1" == "tag" ]; then
    export PHABEL_TARGET=$(git rev-parse --abbrev-ref HEAD | sed 's/master-//g')
    export PATH="$HOME/.local/php/$PHABEL_TARGET:$PATH"
fi

mkdir -p $HOME/.ssh
ssh-keyscan -t rsa github.com >> $HOME/.ssh/known_hosts
echo "$DEPLOY_KEY" > $HOME/.ssh/id_rsa
chmod 0600 $HOME/.ssh/id_rsa
git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
git config --global user.name "Github Actions"
git remote rm origin
git remote add origin git@github.com:phabelio/phabel.git

php -v
php tools/ci/prepareDeps.php
php vendor/bin/phpunit

if [ "$1" == "convert" ]; then
    export shouldTag=$(git tag --sort=-creatordate | head -1)
    php tools/convertPhabel.php $PHABEL_TARGET
else
    export shouldTag=$(git log --format=%B -n 1 $(git log -1 --pretty=%H) | sed 's/.*[(]tag //g;s/[)].*//g')
    git tag $shouldTag
    git push origin $shouldTag
fi
