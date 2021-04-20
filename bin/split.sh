#!/usr/bin/env bash

set -e
set -x

if [[ -n "${INPUT_GITHUB_TOKEN}" ]]
then
  BASE_REMOTE_URL="https://${GITHUB_ACTOR}:${INPUT_GITHUB_TOKEN}@github.com/acantepie"
else
  BASE_REMOTE_URL="git@github.com:acantepie"
fi

CURRENT_BRANCH="master"

function split()
{
    SHA1=`./bin/splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function remote()
{
    REMOTE_URL="$BASE_REMOTE_URL/$2.git"
    git remote add $1 $REMOTE_URL || true
}

if [[ "$1" == '--pull' ]]
then
  git pull origin "$CURRENT_BRANCH"
fi

remote adminbundle umbrella-adminbundle
remote corebundle umbrella-corebundle
remote skeleton umbrella-skeleton

split 'Bundle/AdminBundle' adminbundle
split 'Bundle/CoreBundle' corebundle
split 'Skeleton' skeleton