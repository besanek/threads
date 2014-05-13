<?php

namespace BesanekTests\Threads\Executables;

require __DIR__ . '/../../bootstrap.php';

use BesanekTests\Threads\TestCase;

class SimpleExecutableTest extends TestCase
{

    public function testCommand()
    {
        $exe = new \Besanek\Threads\Executables\SimpleExecutable('foo', 'bar');
        \Assert::same('foo bar', $exe->getCommand());
    }

}

id(new SimpleExecutableTest)->run();
