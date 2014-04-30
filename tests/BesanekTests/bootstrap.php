<?php

$autoloadPath = __DIR__ . '/../../vendor/autoload.php';

if (is_file($autoloadPath)) {
    $autoload = require_once $autoloadPath;
}

$autoload->add('BesanekTests', __DIR__ . '/..');

if (!class_exists('Tester\Assert')) {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}

if (extension_loaded('xdebug')) {
    xdebug_disable();
    Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}

class_alias('Tester\Assert', 'Assert');

Tester\Environment::setup();

define('TEMP_DIR', __DIR__ . '/tmp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
Tester\Helpers::purge(TEMP_DIR);

function id($class)
{
    return $class;
}
