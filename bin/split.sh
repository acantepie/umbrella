#!/usr/bin/env bash

set -e
set -x

BASE_URL="git@github.com:acantepie"

function push()
{
    PACKAGE_PATH="$1"
    REMOTE_NAME="$2"
    REMOTE_URL="${BASE_URL}/${REMOTE_NAME}.git"

    git remote add "$REMOTE_NAME" "$REMOTE_URL" || true
    SHA1=`splitsh-lite --prefix=$PACKAGE_PATH`
    git push "$REMOTE_NAME" "$SHA1:master" -f
    git remote remove "$REMOTE_NAME"
}

push 'Bundle/AdminBundle' 'umbrella-adminbundle'
push 'Bundle/CoreBundle' 'umbrella-corebundle'