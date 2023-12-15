#!/bin/bash

if [ ! -f .env ]; then
    touch .env
fi

rm composer.lock
rm composer.json

cp ./scripts/composer/composer.10.0.lock ./composer.lock
cp ./scripts/composer/composer.10.0.json ./composer.json

composer install --prefer-install=source > /dev/null 2>&1
php ./vendor/bin/phpunit

rm composer.lock
rm composer.json

cp ./scripts/composer/composer.10.5.lock ./composer.lock
cp ./scripts/composer/composer.10.5.json ./composer.json

composer install --prefer-install=source > /dev/null 2>&1
php ./vendor/bin/phpunit

rm composer.lock
rm composer.json

cp ./scripts/composer/composer.11.0.lock ./composer.lock
cp ./scripts/composer/composer.11.0.json ./composer.json

composer install --prefer-install=source > /dev/null 2>&1
php ./vendor/bin/phpunit

vendor/bin/phpcpd src
vendor/bin/phploc src

vendor/bin/phpcs --config-set colors 1
vendor/bin/phpcs --extensions=php \
    --standard=./vendor/divante-ltd/pimcore-coding-standards/Standards/Pimcore5/ruleset.xml \
    ./src  -s

vendor/bin/phpmd src text \
    ./vendor/divante-ltd/pimcore-coding-standards/Standards/Pimcore5/rulesetmd.xml

./vendor/bin/phpunit --coverage-text --verbose
