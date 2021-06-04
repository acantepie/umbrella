#!/usr/bin/env bash

set -e
set -x

BUILD_DIRECTORY="$HOME/projects/umbrella-skeleton"

rm -Rf "$BUILD_DIRECTORY"
cp -R ./Skeleton "$BUILD_DIRECTORY"
sed -i -e 's/"umbrella2\/corebundle"\:\ ".*"/"umbrella2\/corebundle": "dev-master"/g' "$BUILD_DIRECTORY/composer.json"
sed -i -e 's/"umbrella2\/adminbundle"\:\ ".*"/"umbrella2\/adminbundle": "dev-master"/g' "$BUILD_DIRECTORY/composer.json"

cd "$BUILD_DIRECTORY"

echo 'DATABASE_URL="sqlite:///%kernel.project_dir%/var/database.sqlite"' > "$BUILD_DIRECTORY/.env.local"
composer update
yarn install
yarn build
php bin/console d:s:c
php bin/console d:s:u -f
