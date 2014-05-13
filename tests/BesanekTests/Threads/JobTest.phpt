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

    public function testEditArgumentsAfterCreateJob()
    {
        $executable = new \Besanek\Threads\Executables\PhpExecutable(__DIR__ .'/../Helpers/repeat.php', 'foo');
        $job1 = new \Besanek\Threads\Job($executable, function($out) {
            \Assert::same('foo', trim($out));
        });
        $executable->setArguments('bar');
        $job2 = new \Besanek\Threads\Job($executable, function($out) {
            \Assert::same('bar', trim($out));
        });
        $job1->run();
        $job2->run();
        while(!$job1->isDone()){};
        while(!$job2->isDone()){};
    }
}

id(new JobTest)->run();
