<?php

use Symfony\Component\Filesystem\Filesystem;
use Umbrella\AdminBundle\Tests\App\Kernel;

// needed to avoid encoding issues when running tests on different platforms
setlocale(\LC_ALL, 'en_US.UTF-8');

// needed to avoid failed tests when other timezones than UTC are configured for PHP
date_default_timezone_set('UTC');

$file = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies using Composer to run the test suite.');
}
$autoload = require $file;

$kernel = new Kernel();

// delete the existing cache directory to avoid issues
(new Filesystem())->remove($kernel->getCacheDir());
