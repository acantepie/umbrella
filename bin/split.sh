#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="master"

function split()
{
    SHA1=`./bin/splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function remote()
{
    git remote add $1 $2 || true
}

git pull origin "$CURRENT_BRANCH"

remote adminbundle 'git@github.com:acantepie/umbrella-adminbundle'
remote corebundle 'git@github.com:acantepie/umbrella-corebundle'
remote skeleton 'git@github.com:acantepie/umbrella-skeleton'

split 'Bundle/AdminBundle' adminbundle
split 'Bundle/CoreBundle' corebundle
split 'Skeleton' skeleton