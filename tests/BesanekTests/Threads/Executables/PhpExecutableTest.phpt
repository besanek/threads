<?php

namespace BesanekTests\Threads\Executables;

require __DIR__ . '/../../bootstrap.php';

use BesanekTests\Threads\TestCase;

class PhpExecutableTest extends TestCase
{

    public function testDetectByConstant()
    {
        if (defined('PHP_BINARY') === false) {
            define('PHP_BINARY', '/usr/sbin/php');
        }

        if(is_executable(PHP_BINARY) === false)
        {
            \Tester\Environment::Skip();
        }

        $phpExecutable = new \Besanek\Threads\Executables\PhpExecutable('/foo/bar');

        \Assert::same(PHP_BINARY, $phpExecutable->getExecutable());
    }
}

id(new PhpExecutableTest)->run();
