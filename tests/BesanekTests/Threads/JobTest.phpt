<?php

namespace BesanekTests\Threads;

require __DIR__ . '/../bootstrap.php';

use BesanekTests\Threads\TestCase;

class JobTest extends TestCase
{

    public function testOkCallback()
    {
        $executable = new \Besanek\Threads\Executables\PhpExecutable(__DIR__ .'/../Helpers/ok.php');

        $called = false;

        $job = new \Besanek\Threads\Job($executable, function () use (&$called) {
            $called = true;
        }, null);

        $job->run();
        while(!$job->isDone()){};

        \Assert::true($called);
    }

    public function testFailCallback()
    {
        $executable = new \Besanek\Threads\Executables\PhpExecutable(__DIR__ .'/../Helpers/fail.php');

        $called = false;

        $job = new \Besanek\Threads\Job($executable, null, function () use (&$called) {
            $called = true;
        });

        $job->run();
        while(!$job->isDone()){};

        \Assert::true($called);
    }
}

id(new JobTest)->run();
