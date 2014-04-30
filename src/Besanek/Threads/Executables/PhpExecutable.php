<?php

namespace Besanek\Threads\Executables;

use Besanek\Threads\LogicException;

/**
 * @author Robert Jelen
 */
class PhpExecutable extends BaseExecutable {

    /**
     * @param string $file
     * @param string $arguments
     * @param string $phpBin
     * @throws LogicException
     */
    public function __construct($file, $arguments = null, $phpBin = null)
    {
        if (empty($phpBin)) {
            $phpBin = $this->detectPhpBinary();
        }
        if (is_executable($phpBin) === false) {
            if(is_file($phpBin)) {
                throw new LogicException(sprinf('Binary %s can not be execute', $phpBin));
            }
            throw new LogicException(sprinf('%s is not file', $phpBin));
        }

        $this->file = $file;
        $this->arguments = $arguments;
        $this->executable = $phpBin;
    }

    /**
     * @return string
     * @throws LogicException
     */
    protected function detectPhpBinary() {
        if(defined('PHP_BINARY') && is_executable(PHP_BINARY)) {
            return PHP_BINARY;
        }
        throw new LogicException('PHP binary can not be detect, please enter them manualy');
    }

}