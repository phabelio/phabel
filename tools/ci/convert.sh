#!/bin/bash -e

export PHP_CS_FIXER_IGNORE_ENV=1
export PHABEL_PARALLEL=1

mkdir -p $HOME/.ssh
ssh-keyscan -t rsa github.com >> $HOME/.ssh/known_hosts
echo "$DEPLOY_KEY" > $HOME/.ssh/id_rsa
chmod 0600 $HOME/.ssh/id_rsa
git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
git config --global user.name "Github Actions"
git remote rm origin
git remote add origin git@github.com:phabelio/phabel.git

run() {
    php -v
    php tools/ci/prepareDeps.php
    php vendor/bin/phpunit
}


run

php tools/convertPhabel.php $PHABEL_TARGET

TAG=$(php tools/ci/getTag.php $(git tag --sort=-creatordate | head -1) $PHABEL_TARGET)

export PATH="$HOME/.local/php/$PHABEL_TARGET:$PATH"

git clean -ffdx
git reset --hard
git fetch master-$(echo $PHABEL_TARGET | sed 's/\.//g')
git checkout master-$(echo $PHABEL_TARGET | sed 's/\.//g')

run

git tag $TAG
git push origin $TAG
