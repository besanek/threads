<?php

namespace BesanekTests\Threads\Executables;

require __DIR__ . '/../../bootstrap.php';

use BesanekTests\Threads\TestCase;

class PhpExecutableTest extends TestCase
{

    public function testCommand()
    {
        $exe = new \Besanek\Threads\Executables\PhpExecutable('/foo/bar', 'foo', 'php-cli', '-v');
        \Assert::same('php-cli -v /foo/bar foo', $exe->getCommand());
    }

    public function testDetectWorkDir()
    {
        $exe = new \Besanek\Threads\Executables\PhpExecutable('/foo/bar');
        \Assert::same('/foo', $exe->getWorkDir());
     }

     public function testWorkDirSet()
    {
        $exe = new \Besanek\Threads\Executables\PhpExecutable('/foo/bar');
        $exe->setWorkDir(__DIR__);
        \Assert::same(__DIR__, $exe->getWorkDir());
     }

     public function testWorkDirSetFile()
    {
        $exe = new \Besanek\Threads\Executables\PhpExecutable('/foo/bar');
        $exe->setWorkDir(__FILE__);
        \Assert::same(__DIR__, $exe->getWorkDir());
     }

    public function testSetArguments()
    {
        $exe = new \Besanek\Threads\Executables\PhpExecutable('/foo/bar', 'foo', 'php-cli', '-v');
        $exe->setArguments('bar');
        \Assert::same('php-cli -v /foo/bar bar', $exe->getCommand());
     }
}

id(new PhpExecutableTest)->run();
