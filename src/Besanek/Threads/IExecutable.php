<?php

namespace Besanek\Threads;

/**
 * @author Robert Jelen
 */
interface IExecutable {

    public function getCommand();
    public function getWorkdir();
    public function getEnviroment();
    public function isValidReturnCode($returnCode);

}