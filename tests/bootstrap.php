<?php

use Pimcore\Tests\Util\Autoloader as Autoloader10;
use Pimcore\Tests\Support\Util\Autoloader as Autoloader11;

define('PIMCORE_PROJECT_ROOT', realpath(__DIR__ . '/..'));

include PIMCORE_PROJECT_ROOT . '/vendor/autoload.php';

\Pimcore\Bootstrap::setProjectRoot();
\Pimcore\Bootstrap::bootstrap();

if (file_exists(PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/Support/Util/Autoloader.php')) {
    require_once PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/Support/Util/Autoloader.php';
    Autoloader11::addNamespace('Pimcore\Tests', PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/Support');
} elseif (file_exists(PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/_support/Util/Autoloader.php')) {
    require_once PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/_support/Util/Autoloader.php';
    Autoloader10::addNamespace('Pimcore\Tests', PIMCORE_PROJECT_ROOT . '/vendor/pimcore/pimcore/tests/_support');
}
