<?php

namespace Besanek\Threads\Executables;

use Besanek\Threads\LogicException;

/**
 * @author Robert Jelen
 */
class SimpleExecutable extends BaseExecutable {

    /**
     * @param string $binary
     * @param string $arguments
     */
    public function __construct($binary, $arguments = '')
    {
        $this->executable = $binary;
        $this->arguments = $arguments;
    }

}