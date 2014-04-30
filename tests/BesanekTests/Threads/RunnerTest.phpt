<?php

namespace BesanekTests\Threads;

require __DIR__ . '/../bootstrap.php';

use BesanekTests\Threads\TestCase;

class RunnerTest extends TestCase
{

    public function testThreadsLimit()
    {
        $executable = new \Besanek\Threads\Executables\PhpExecutable(__DIR__ .'/../Helpers/sleep.php 1');
        $runner = new \Besanek\Threads\Runner(2);

        $done = 0;
        for ($i=0;$i<5;$i++) {
            $runner->addJob(new \Besanek\Threads\Job($executable, function () use (&$done) {
                $done++;
            }));
        }

        $runner->process();
        $count = trim(exec(sprintf('ps -o ppid| grep -w %s | wc -l', getmypid()), $out)) - 1; // -1 because grep match self
        \Assert::same(2, $count);
    }

    public function testCleanAfterSelf()
    {
        $executable = new \Besanek\Threads\Executables\PhpExecutable(__DIR__ .'/../Helpers/ok.php');
        $runner = new \Besanek\Threads\Runner(2);

        $done = 0;
        for ($i=0;$i<10;$i++) {
            $runner->addJob(new \Besanek\Threads\Job($executable, function () use (&$done) {
                $done++;
            }));
        }

        unset($runner);

        \Assert::same(10, $done);
    }

    public function testStress()
    {
        $executable = new \Besanek\Threads\Executables\PhpExecutable(__DIR__ .'/../Helpers/ok.php');
        $runner = new \Besanek\Threads\Runner();

        for ($i=1;$i<=100;$i++) {
            $runner->addJob(new \Besanek\Threads\Job($executable, function () use (&$done) {
                $done++;
            }));
            if($i%50 === 0){
                $runner->process();
            }
        }

        unset($runner);

        \Assert::same(100, $done);
    }
}

id(new RunnerTest)->run();
