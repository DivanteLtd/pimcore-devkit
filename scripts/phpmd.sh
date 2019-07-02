#!/bin/bash

echo -e "\e[34m=> PHP Mess Detector \e[0m"

vendor/bin/phpmd src text \
    ./vendor/divante-ltd/pimcore-coding-standards/Standards/Pimcore5/rulesetmd.xml
